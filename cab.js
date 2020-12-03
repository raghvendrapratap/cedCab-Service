$(function() {

    $("#pickupMsg1").hide();
    $("#dropMsg1").hide();
    $("#typeMsg").hide();
    $("#luggageMsg").hide();

    $("#type").change(function() {
        $("#book").hide();
        $("#submit").show();
        var cabType = $("#type").val();
        if (cabType == "CedMicro") {
            $("#luggageMsg").show();
            $("#luggage").attr('disabled', 'disabled');
            $("#luggage").val(null);
        } else {
            $("#luggageMsg").hide();
            $("input").removeAttr('disabled');
        }
    });

    $(".onlytext").bind("keypress", function(e) {
        var keyCode = e.which ? e.which : e.keyCode

        if (!(keyCode >= 48 && keyCode <= 57)) {
            $(".error").css("display", "inline");
            return false;
        } else {
            $(".error").css("display", "none");
        }
    });
    $("#luggage").keyup(function() {
        $("#book").hide();
        $("#submit").show();
    });

    $(".options").change(function() {
        $("#book").hide();
        $("#submit").show();
        $("select option").prop("disabled", false);
        $(".options").not($(this)).find("option[value='" + $(this).val() + "']").prop("disabled", true);
    });
});


$(function() {

    $("#submit").click(function(x) {
        $("#pickupMsg1").hide();
        $("#dropMsg1").hide();
        $("#typeMsg").hide();
        x.preventDefault();

        var pickupLocation = $("#pickup").val();
        var dropLocation = $("#drop").val();
        var cabType = $("#type").val();
        var luggage = $("#luggage").val();

        if (pickupLocation == "" || dropLocation == "" || cabType == "") {

            if (pickupLocation == "") {
                $("#pickupMsg1").show();
            }
            if (cabType == "") {
                $("#typeMsg").show();
            }
            if (dropLocation == "") {
                $("#dropMsg1").show();
            }

        } else {
            $("#submit").attr('data-target', '#exampleModalCenter');
            $.ajax({
                url: 'cab.php',
                type: 'POST',
                data: {
                    pickupLocation: pickupLocation,
                    dropLocation: dropLocation,
                    cabType: cabType,
                    luggage: luggage
                },
                dataType: "json",
                success: function(result) {
                    console.log(result);
                    $("#luggageid").val(result['luggage']);
                    $("#fare").val(result['fare']);
                    $("#distance").val(result['distance']);
                    $("#result").html("  Your Fare : Rs. " + result['fare']);
                    $("#submit").hide();
                    $("#bookNow").html('<input type="submit" class="btn-success btn-lg btn-block" id="book" name="book" value="Book Now">');
                },
                error: function() {
                    $("#book").hide();
                    $("#result").html("Pickup/Drop cant be same.");
                }
            })
        }
    });
});