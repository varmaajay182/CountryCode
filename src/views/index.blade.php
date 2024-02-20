<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <title>Document</title>
</head>

<body>

    <div class="container  mt-3">
        <h1 class="text-center">Country Code And Number Validation </h1>
        <div id="messageContainer"></div>
        <form action="" id="myForm">
            {{-- @CSRF --}}

            <div class="d-flex justify-content-center align-items-center">
                <div class=" d-flex justify-content-center align-items-center" style="margin-top: 30px; width:50%">


                    <select class="form-select" style="width: 15%" id="countrySelect" name="countrycode">
                        @foreach ($countryData as $country)
                            <option value="{{ $country['countryCode'] }}" data-image="{{ asset($country['flag']) }}">
                                + {{ $country['countryCode'] }}</option>
                        @endforeach
                    </select>

                    <input type="number" class="form" style="width: 73%" placeholder="Enter the number"
                        name="number">
                </div>

            </div>
            <div class="text-center mt-3"> <button type="submit" id="submitBtn" class="btn btn-primary">Submit</button>
            </div>

        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#countrySelect').select2({
                templateResult: formatCountryOption,
                escapeMarkup: function(markup) {
                    return markup;
                },
                minimumResultsForSearch: -1
            });
        });


        function formatCountryOption(country) {
            if (!country.id) {
                return country.text;
            }

            var imageWidth = 20;
            var imageHeight = 20;

            var $country = $(
                '<span><img src="' + $(country.element).data('image') + '" class="img-flag" style="width: ' +
                imageWidth + 'px; height: ' + imageHeight + 'px;" /> ' + country.text + '</span>'
            );
            return $country;
        }
        $(document).ready(function() {
            $('#submitBtn').click(function(e) {
                e.preventDefault();

                var formData = $('#myForm').serialize();

                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                console.log(formData, 'formData')
                $.ajax({
                    type: 'POST',
                    url: "{{ route('validate') }}",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        var messageContainer = $('#messageContainer');

                        if (response.success) {
                            messageContainer.html('<div class="alert alert-success">' + response
                                .success + '</div>');
                        } else {
                            messageContainer.html('<div class="alert alert-danger">' + response
                                .error + '</div>');
                        }

                        setTimeout(function() {
                            messageContainer.html('');
                        }, 2000);
                    },
                    error: function(error) {
                        console.log(error, 'error');
                    }
                });
            });
        });
    </script>

</body>

</html>
