<!--
Sander de Wilde
-->

<?php

class statistieken {

    var $user;
    var $maand;
    var $maanden = array("", "Januari", "Februari", "Maart", "April", "Mei", "Juni", "Juli", "Augustus", "September", "Oktober", "November", "December");

    function statistieken($maand) {
        ## Variabel updaten
        $this->maand = $maand;
        $this->user = $_SERVER['REMOTE_ADDR'];

        ## Is er al een record voor deze maand?
        $sStats = mysql_query("SELECT * FROM statistieken WHERE maand='" . $this->maand . "'") or die(mysql_error());
        if (mysql_num_rows($sStats)) {
            ## Gegevens fetchen
            $fStats = mysql_fetch_assoc($sStats);

            ## Kijken of deze gebruiker al in de tabel staat
            $Bezoekers = explode(",", $fStats['bezoekers']);
            if (count($Bezoekers) > 0) {
                ## Staat het IP van de gebruiker al in de array, zo ja dan enkel een hit bijtellen.
                if (in_array($this->user, $Bezoekers))
                    $this->_new_hit();
                else
                    $this->_new_visiter($Bezoekers);
            }
        }
        else {
            ## Er is nog geen record voor deze maand dus deze moeten we aanmaken
            $this->_new_record();
        }
    }

    function _new_hit() {
        ## Tabel van deze maand updaten en 1 hit bijtellen
        mysql_query("   UPDATE
                        statistieken
                SET
                        hits = hits+1
                WHERE
                        maand = '" . $this->maand . "'") or die(mysql_error());
    }

    function _new_visiter($Bezoekers) {
        ## Tabel van deze maand updaten en 1 hit, 1 unieke bezoeker en 1 IP updaten.
        mysql_query("   UPDATE
                        statistieken
                SET
                        hits = hits+1,
                        uniekB = uniekB + 1,
                        bezoekers = '" . $Bezoekers . "," . $this->user . "'
                WHERE
                        maand = '" . $this->maand . "'") or die(mysql_error());
    }

    function _new_record() {
        ## Een nieuwe row toevoegen
        mysql_query("   INSERT INTO
                        statistieken
                (
                        maand,
                        hits,
                        uniekB,
                        bezoekers
                )
                VALUES
                (
                        '" . $this->maand . "',
                        '1',
                        '1',
                        '" . $this->user . ",'
                )   ") or die(mysql_error());
    }

    function show_stats($sMaanden) {
        $sTotaal = mysql_query("SELECT
                            SUM(hits) AS tHits,
                            SUM(uniekB) AS tVisits
                    FROM
                            statistieken") or die(mysql_error());
        $fTotaal = mysql_fetch_assoc($sTotaal);

        $sStats = mysql_query("  SELECT
                            hits,
                            uniekB,
                            maand
                     FROM
                            statistieken
                     ORDER BY
                            maand ASC
                     LIMIT
                            0," . $sMaanden) or die(mysql_error());
        if (mysql_num_rows($sStats)) {
            $output = "<b>Totaal:</b><br />\n";
            $output .= $fTotaal['tHits'] . "<br />\n";
            $output .= $fTotaal['tVisits'] . "<br /><br />\n";

            while ($fStats = mysql_fetch_assoc($sStats)) {
                ## De maand laten zien d.m.v exploden
                $maand = explode("-", $fStats['maand']);

                $output .= '<table border="1" width="100%">' . "\n";
                $output .= '    <tr>' . "\n";
                $output .= '        <td width="100%" colspan="2"><b>' . $this->maanden[$maand[1]] . '</b></td>' . "\n";
                $output .= '    </tr>' . "\n";
                $output .= '    <tr>' . "\n";
                $output .= '        <td width="30%">' . $fStats['hits'] . '</td>' . "\n";
                $output .= '        <td width="60%">' . "\n";
                $output .= '            <div style="margin-top: 4px">' . "\n";
                $output .= '                <table height="10" cellspacing="0" cellpadding="0" width="100" border="0">' . "\n";
                $output .= '                    <tr>' . "\n";
                $output .= '                        <td width="' . round($fStats['hits'] / $fTotaal['tHits'] * 100) . '%" bgcolor="red"></td>' . "\n";
                $output .= '                        <td width="' . (100 - round($fStats['hits'] / $fTotaal['tHits'] * 100)) . '%"></td>' . "\n";
                $output .= '                    </tr>' . "\n";
                $output .= '                </table>' . "\n";
                $output .= '            </div>' . "\n";
                $output .= '        </td>' . "\n";
                $output .= '        <td width="10%">' . round($fStats['hits'] / $fTotaal['tHits'] * 100) . '%</td>' . "\n";
                $output .= '    </tr>' . "\n";
                $output .= '    <tr>' . "\n";
                $output .= '        <td width="30%">' . $fStats['uniekB'] . '</td>' . "\n";
                $output .= '        <td width="60%">' . "\n";
                $output .= '            <div style="margin-top: 4px">' . "\n";
                $output .= '                <table height="10" cellspacing="0" cellpadding="0" width="100" border="0">' . "\n";
                $output .= '                    <tr>' . "\n";
                $output .= '                        <td width="' . round($fStats['uniekB'] / $fTotaal['tVisits'] * 100) . '%" bgcolor="red"></td>' . "\n";
                $output .= '                        <td width="' . (100 - round($fStats['uniekB'] / $fTotaal['tVisits'] * 100)) . '%"></td>' . "\n";
                $output .= '                    </tr>' . "\n";
                $output .= '                </table>' . "\n";
                $output .= '            </div>' . "\n";
                $output .= '        </td>' . "\n";
                $output .= '        <td width="10%">' . round($fStats['uniekB'] / $fTotaal['tVisits'] * 100) . '%</td>' . "\n";
                $output .= '    </tr>' . "\n";
                $output .= '</table><br />' . "\n";
            }
            return $output;
        }
    }

}
?>