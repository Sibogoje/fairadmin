<?php

if (!function_exists('fund_summary_request_from_post')) {
    function fund_summary_request_from_post()
    {
        return [
            'from' => $_POST['from'] ?? '',
            'to' => $_POST['to'] ?? '',
            'fund_id' => $_POST['fund_id'] ?? 'all',
        ];
    }
}

if (!function_exists('fund_summary_fetch')) {
    function fund_summary_fetch($conn, $from, $to, $fundId)
    {
        $sql = "
            SELECT
                f.RetirementFundID,
                f.FundName,
                COUNT(DISTINCT m.MemberID) AS TotalMembers,
                SUM(
                    COALESCE(
                        (
                            SELECT ma_before.NewBalance
                            FROM tblmemberaccounts AS ma_before
                            WHERE ma_before.memberID = m.MemberID
                              AND DATE(ma_before.TransactionDate) < ?
                            ORDER BY DATE(ma_before.TransactionDate) DESC, ma_before.accountsID DESC
                            LIMIT 1
                        ),
                        (
                            SELECT ma_first.StartingBalance
                            FROM tblmemberaccounts AS ma_first
                            WHERE ma_first.memberID = m.MemberID
                              AND DATE(ma_first.TransactionDate) BETWEEN ? AND ?
                            ORDER BY DATE(ma_first.TransactionDate) ASC, ma_first.accountsID ASC
                            LIMIT 1
                        ),
                        0
                    )
                ) AS OpeningBalance,
                SUM(
                    COALESCE(
                        (
                            SELECT ma_close.NewBalance
                            FROM tblmemberaccounts AS ma_close
                            WHERE ma_close.memberID = m.MemberID
                              AND DATE(ma_close.TransactionDate) <= ?
                            ORDER BY DATE(ma_close.TransactionDate) DESC, ma_close.accountsID DESC
                            LIMIT 1
                        ),
                        0
                    )
                ) AS ClosingBalance
            FROM tblretirementfunds AS f
            LEFT JOIN tbldeceased AS d ON d.RetirementFundID = f.RetirementFundID
            LEFT JOIN tblmembers AS m ON m.DeceasedID = d.DeceasedID
            WHERE m.MemberID IS NOT NULL
              AND EXISTS (
                    SELECT 1
                    FROM tblmemberaccounts AS ma_exists
                    WHERE ma_exists.memberID = m.MemberID
                      AND DATE(ma_exists.TransactionDate) <= ?
              )
        ";

        $types = 'sssss';
        $params = [$from, $from, $to, $to, $to];

        if ($fundId !== 'all') {
            $sql .= ' AND f.RetirementFundID = ?';
            $types .= 's';
            $params[] = $fundId;
        }

        $sql .= '
            GROUP BY f.RetirementFundID, f.FundName
            ORDER BY f.FundName ASC
        ';

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            return ['statement' => false, 'result' => null];
        }

        $stmt->bind_param($types, ...$params);
        $stmt->execute();

        return ['statement' => $stmt, 'result' => $stmt->get_result()];
    }
}