/*jshint esversion: 6 */
/*global console*/
$(function () {
    "use strict";
    function isNumeric(n){
        let x = !isNaN(parseFloat(n)) && isFinite(n);
        console.log(x);
        return x;
    }
    function validateX(){
        if ($('.x-checkbox').is(':checked')){
            $('xbox-label').removeClass('box-error');
            return true;
        } else {
            $('xbox-label').addClass('box-error');
            return false;
        }
    }
    function validateY(){
        const Y_MAX = 3;
        const Y_MIN = -3;

        let yField = $('#y-value');
        let numY = yField.val().replace(',','.');

        if (isNumeric(numY) && numY >= Y_MIN && numY <= Y_MAX){
            yField.removeClass('text-error');
            return true;
        } else {
            yField.addClass('text-error');
            return false;
        }
    }
    function validateR(){
        const R_MAX = 5;
        const R_MIN = 2;

        let rField = $('#r-value');
        let numR = rField.val().replace(',', '.');

        if (isNumeric(numR) && numR >= R_MIN && numR <= R_MAX){
            rField.removeClass('text-error');
            return true;
        } else {
            rField.addClass('text-error');
            return false;
        }
    }
    function validateForm(){
        console.log("Validating...");
        return validateR() && validateX() && validateY();
    }
    $(".x-checkbox").change(function (){
            $(".x-checkbox").prop('checked', false);
            $(this).prop('checked', true);
            $(".x-checkbox").not(this).prop('checked', false);
        });
    $('#inputForm').on('submit', function(event) {
        event.preventDefault();
        let timeZoneOffset = new Date().getTimezoneOffset();
        if(!validateForm()) {
            return;
        }
        $.ajax({
            url: 'script.php',
            type: 'POST',
            data: $(this).serialize() + '&timezone=' + timeZoneOffset,
            dataType: "json",
            beforeSend: function(){
                $('#submit-button').attr('disabled','disabled');
                console.log(this.data);
            },
            error : function(error) {
                console.log("exception",error);
            },
            success: function(data){
                console.log("sent");
                $('#submit-button').attr('disabled',false);
                console.log(data);
                let newRow;
                if (data.validate) {
                    newRow = '<tr>';
                    newRow += '<td>' + data.xval + '</td>';
                    newRow += '<td>' + data.yval + '</td>';
                    newRow += '<td>' + data.rval + '</td>';
                    newRow += '<td>' + data.cTime + '</td>';
                    newRow += '<td>' + data.exTime + '</td>';
                    newRow += '<td>' + data.hitRes + '</td>';
                    $('#resultTable').append(newRow);
                    let key = localStorage.length+1;
                    localStorage.setItem(key.toString(),newRow);
                }
            }
        });
    });
});


for (let i = 1; i <= localStorage.length; i++){
    $('#resultTable').append(localStorage.getItem(i.toString()));
}