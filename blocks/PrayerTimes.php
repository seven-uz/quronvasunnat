<?php
/**
 * PrayerTimes — namoz vaqtlarini astronomik hisoblovchi sof PHP klass.
 *
 * Tashqi API yoki kutubxonaga bog'liq emas. Quyosh holatini (deklinatsiya va
 * vaqt tenglamasi) hisoblash orqali berilgan koordinata, sana va vaqt mintaqasi
 * uchun Bomdod, Quyosh chiqishi, Peshin, Asr, Shom va Xufton vaqtlarini topadi.
 *
 * Algoritm asosi: PrayTimes.org (GNU GPL, ochiq manba) algoritmining PHP portasi.
 * O'zbekiston uchun standart: Fajr/Isha burchagi 18°, Asr — Hanafiy (omil = 2).
 */
class PrayerTimes
{
    // Hisoblash burchaklari
    private $fajrAngle = 18.0;   // Bomdod uchun quyosh ufqdan past burchagi
    private $ishaAngle = 18.0;   // Xufton uchun quyosh ufqdan past burchagi
    private $asrFactor = 2;      // 1 = Shofe'iy, 2 = Hanafiy (O'zbekiston)

    /**
     * @param float $angleFajr Bomdod burchagi (gradus)
     * @param float $angleIsha Xufton burchagi (gradus)
     * @param int   $asrFactor 1 (Shofe'iy) yoki 2 (Hanafiy)
     */
    public function __construct($angleFajr = 18.0, $angleIsha = 18.0, $asrFactor = 2)
    {
        $this->fajrAngle = (float) $angleFajr;
        $this->ishaAngle = (float) $angleIsha;
        $this->asrFactor = ($asrFactor == 1) ? 1 : 2;
    }

    /* ----------------------- Trigonometriya yordamchilari ----------------------- */
    private function dtr($d) { return $d * M_PI / 180.0; }
    private function rtd($r) { return $r * 180.0 / M_PI; }
    private function sin($d) { return sin($this->dtr($d)); }
    private function cos($d) { return cos($this->dtr($d)); }
    private function tan($d) { return tan($this->dtr($d)); }
    private function arcsin($x) { return $this->rtd(asin($x)); }
    private function arccos($x) { return $this->rtd(acos($x)); }
    private function arccot($x) { return $this->rtd(atan2(1.0, $x)); }
    private function fixHour($h) { $h = fmod($h, 24.0); return $h < 0 ? $h + 24.0 : $h; }

    /** Julian sanasini hisoblash */
    private function julian($year, $month, $day)
    {
        if ($month <= 2) { $year -= 1; $month += 12; }
        $A = floor($year / 100.0);
        $B = 2 - $A + floor($A / 4.0);
        return floor(365.25 * ($year + 4716)) + floor(30.6001 * ($month + 1)) + $day + $B - 1524.5;
    }

    /**
     * Quyoshning deklinatsiyasi (D) va vaqt tenglamasi (EqT) — berilgan Julian sana uchun.
     * @return array [deklinatsiya, vaqt_tenglamasi]
     */
    private function sunPosition($jd)
    {
        $D = $jd - 2451545.0;
        $g = $this->fixAngle(357.529 + 0.98560028 * $D);
        $q = $this->fixAngle(280.459 + 0.98564736 * $D);
        $L = $this->fixAngle($q + 1.915 * $this->sin($g) + 0.020 * $this->sin(2 * $g));

        $e = 23.439 - 0.00000036 * $D;
        $RA = $this->rtd(atan2($this->cos($e) * $this->sin($L), $this->cos($L))) / 15.0;
        $RA = $this->fixHour($RA);

        $decl = $this->arcsin($this->sin($e) * $this->sin($L));
        $eqt  = $q / 15.0 - $RA;
        return array($decl, $eqt);
    }

    private function fixAngle($a) { $a = fmod($a, 360.0); return $a < 0 ? $a + 360.0 : $a; }

    /** Quyosh berilgan burchakda bo'lganidagi vaqt (peshindan farq, soat) */
    private function sunAngleTime($angle, $decl, $lat)
    {
        $t = (1.0 / 15.0) * $this->arccos(
            (-$this->sin($angle) - $this->sin($decl) * $this->sin($lat)) /
            ($this->cos($decl) * $this->cos($lat))
        );
        return $t;
    }

    /** Asr vaqti (peshindan farq, soat) — soyaga asoslangan */
    private function asrTime($factor, $decl, $lat)
    {
        $angle = -$this->arccot($factor + $this->tan(abs($lat - $decl)));
        return $this->sunAngleTime($angle, $decl, $lat);
    }

    /**
     * Berilgan sana va joy uchun namoz vaqtlarini hisoblaydi.
     *
     * @param int    $year
     * @param int    $month  1-12
     * @param int    $day
     * @param float  $lat    kenglik
     * @param float  $lng    uzunlik
     * @param float  $tz     vaqt mintaqasi (O'zbekiston = 5)
     * @return array ['fajr','sunrise','dhuhr','asr','maghrib','isha'] => "HH:MM"
     */
    public function getTimes($year, $month, $day, $lat, $lng, $tz = 5.0)
    {
        $jd = $this->julian($year, $month, $day) - $lng / (15.0 * 24.0);
        list($decl, $eqt) = $this->sunPosition($jd + 0.5);

        // Peshin (Dhuhr) — mahalliy quyosh peshini + 0 daqiqa
        $dhuhr = 12.0 - $eqt;

        $fajr    = $dhuhr - $this->sunAngleTime($this->fajrAngle, $decl, $lat);
        $sunrise = $dhuhr - $this->sunAngleTime($this->riseSetAngle($lat), $decl, $lat);
        $sunset  = $dhuhr + $this->sunAngleTime($this->riseSetAngle($lat), $decl, $lat);
        $asr     = $dhuhr + $this->asrTime($this->asrFactor, $decl, $lat);
        $maghrib = $sunset;
        $isha    = $dhuhr + $this->sunAngleTime($this->ishaAngle, $decl, $lat);

        // Vaqt mintaqasi va uzunlikka ko'ra mahalliy vaqtga keltirish
        $adjust = function ($t) use ($lng, $tz) {
            return $this->fixHour($t + $tz - $lng / 15.0);
        };

        return array(
            'fajr'    => $this->format($adjust($fajr)),
            'sunrise' => $this->format($adjust($sunrise)),
            'dhuhr'   => $this->format($adjust($dhuhr)),
            'asr'     => $this->format($adjust($asr)),
            'maghrib' => $this->format($adjust($maghrib)),
            'isha'    => $this->format($adjust($isha)),
        );
    }

    /** Quyosh chiqishi/botishi burchagi (atmosfera refraksiyasi + balandlik) */
    private function riseSetAngle($lat) { return 0.833; }

    /** Soatni "HH:MM" matniga aylantirish (eng yaqin daqiqaga) */
    private function format($hours)
    {
        if (is_nan($hours)) return '-';
        $hours = $this->fixHour($hours + 0.5 / 60.0); // eng yaqin daqiqaga yaxlitlash
        $h = floor($hours);
        $m = floor(($hours - $h) * 60.0);
        return sprintf('%02d:%02d', $h, $m);
    }
}
