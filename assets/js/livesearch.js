$(document).ready(function(){
    $('.search-box input[type="search"]').on("keyup input", function(){
        var inputVal = $(this).val();
        var resultDropdown = $(this).siblings(".result");
        if(inputVal.length){
            $.get("livesearch.php", {term: inputVal}).done(function(data){
                resultDropdown.html(data);
            });
        } else{
            resultDropdown.empty();
        }
    });
    $(document).on("click", ".result a", function(){
        $(this).parents(".search-box").find('input[type="search"]').val($(this).text());
        $(this).parent(".result").empty();
    });
});