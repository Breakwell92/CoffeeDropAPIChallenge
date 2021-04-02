$(function() {
    // Fetch csrf token for use in ajax requests
    var csrf_token = $('meta[id="token"]').attr('content');

    /*
    *   Location Searching
    */
    function searchPostcodes() {
        // Fetch the user's inputted postcode
        var postcode = $('input[name="postcode"]').val();
        
        $.ajax({
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            url: "/get-nearest-location",
            data: {
                postcode: postcode,
            },
            beforeSend: function() {
                $('#find-location .results').html('<h6>Loading</h6>');
            },
            success: function(result) {
                renderOpeningTimes(result.data);
            },
            error: function(error) {
                $('#find-location .results').html('<h3 class="text-danger">'+error.responseJSON.message+'</h3>');
            }
        });
    };
    // HTML renderer
    function renderOpeningTimes(location) {
        
        var html = [];
        html.push('<h5 class="card-title">Your nearest location is <u>'+location.postcode+'</u></h5>');
        html.push('<h6 class="card-title">This location is '+Math.round(location.distance)+' miles from you</h6>');
        html.push('<h6 class="card-title">Opening times:</h6>');
        
        $.each(location.opening_times, function(index, item) {
            html.push('<p class="card-text">'+item.day_and_opening_times+'</p>');
        });

        $('#find-location .results').html(html.join(''));
    }
    // Button listener
    $('#find-location .submit').on('click', searchPostcodes);

    /*
    *   Cashback Calculator
    */
    function CalculateCashback() {
        var cashback_data = $('#calculate-cashback form').serialize();

        $.ajax({
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            url: "/calculate-cashback",
            data: cashback_data,
            beforeSend: function() {
                $('#calculate-cashback .results').html('<h6>Loading</h6>');
            },
            success: function(result) {
                renderCashbackCalculation(result);
            },
            error: function(error) {
                $('#calculate-cashback .results').html('<h3 class="text-danger">'+error.responseJSON.message+'</h3>');
            }
        });
    };
    // HTML renderer
    function renderCashbackCalculation(result) {
        var html = '<h5 class="card-title">Your cashback amount would be:  <u>&pound;'+result.toFixed(2)+'</u></h5>';
        $('#calculate-cashback .results').html(html);
    }
    // Button listener
    $('#calculate-cashback .submit').on('click', CalculateCashback);


    /*
    *   Add new location
    */    
    function submitLocation() {
        var location_data = $('#new-location form').serialize();

        $.ajax({
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            url: "/create-location",
            data: location_data,
            beforeSend: function() {
                $('#new-location .results').html('<h6>Loading</h6>');
            },
            success: function(result) {
                renderNewLocation(result.data);
            },
            error: function(error) {
                $('#new-location .results').html('<h3 class="text-danger">'+error.responseJSON.message+'</h3>');
            }
        });
    };
    // HTML renderer
    function renderNewLocation(location) {
        
        var html = [];
        html.push('<h5 class="card-title">New location added at <u>'+location.postcode+'</u></h5>');
        
        $.each(location.opening_times, function(index, item) {
            html.push('<p class="card-text">'+item.day_and_opening_times+'</p>');
        });

        $('#new-location .results').html(html.join(''));
    }
    // Button listener
    $('#new-location .submit').on('click', submitLocation);

});