<div class="topBlock">
	<div class="col-12">
		<div class="bg-white p-3 rounded d-md-flex justify-content-between">
			<h6 class="m-0 pb-md-0 pb-2"><?= word('Milodiy') . ': ' . date("d") . '-' . cmn(date("m")) . ' ' . date("Y") . '-yil. ' . wn() ?></h6>
			<h6 class="m-0 d-md-block d-none"><?= $words['bugun'] ?></h6>
			<h6 class="m-0"><?= word('Xijriy') . ': ' . $hijriYear . '-yil ' . $hijriDay . '-' . $hijriMonthName . '. ' . $hijriDayName ?></h6>
		</div>
	</div>
</div>