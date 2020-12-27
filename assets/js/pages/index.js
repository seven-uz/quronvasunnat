$("#playRS").on("click", function () {
	$(this).addClass('d-none');
	$("#pauseRS").removeClass('d-none');
	$("#stopRS").addClass('d-none');
	$("#loopRS").removeClass('d-none');
})
$("#pauseRS").on("click", function () {
	$(this).addClass('d-none');
	$("#playRS").removeClass('d-none');
})
$("#loopRS").on("click", function () {
	$(this).addClass('d-none');
	$("#pauseRS").addClass('d-none');
	$("#playRS").addClass('d-none');
	$("#stopRS").removeClass('d-none');
})
$("#stopRS").on("click", function () {
	$(this).addClass('d-none');
	$("#loopRS").removeClass('d-none');
	$("#playRS").removeClass('d-none');
});

$("#playRD").on("click", function () {
	$(this).addClass('d-none');
	$("#pauseRD").removeClass('d-none');
	$("#stopRD").addClass('d-none');
	$("#loopRD").removeClass('d-none');
});
$("#pauseRD").on("click", function () {
	$(this).addClass('d-none');
	$("#playRD").removeClass('d-none');
});
$("#loopRD").on("click", function () {
	$(this).addClass('d-none');
	$("#pauseRD").addClass('d-none');
	$("#playRD").addClass('d-none');
	$("#stopRD").removeClass('d-none');
});
$("#stopRD").on("click", function () {
	$(this).addClass('d-none');
	$("#loopRD").removeClass('d-none');
	$("#playRD").removeClass('d-none');
});