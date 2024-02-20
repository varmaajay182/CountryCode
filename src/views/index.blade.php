<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        @import url('https:fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&display=swap');

        body {
            height: 100%;
            margin: 0
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        button:focus,
        input:focus {
            outline: none;
            box-shadow: none;
        }

        a,
        a:hover {
            text-decoration: none;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #e0e3e8;

        }


        /*------------*/
        .form-area {
            background-color: #fff;
            box-shadow: 0px 5px 10px rgba(90, 116, 148, 0.3);
            padding: 40px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-area .form-inner {
            width: 100%;
        }

        .form-control {
            display: block;
            width: 100%;
            height: auto;
            padding: 15px 19px;
            font-size: 1rem;
            line-height: 1.4;
            color: #475F7B;
            background-color: #FFF;
            border: 1px solid #DFE3E7;
            border-radius: .267rem;
            -webkit-transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .form-control:focus {
            color: #475F7B;
            background-color: #FFF;
            border-color: #5A8DEE;
            outline: 0;
            box-shadow: 0 3px 8px 0 rgb(0 0 0 / 10%);
        }

        .intl-tel-input,
        .iti {
            width: 100%;
        }
    </style>
    <link rel="stylesheet" href="https:cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https:cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
    <link href="https:cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https:code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https:cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <title>Document</title>
</head>


<body>
    <section class="pt-5 pb-5">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="note">@lang('countrycode::lang.country')</h1>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 mt-5 mb-5">
                    <div class="form-area">
                        <div class="form-inner">
                            <form id="myForm">
                                <div id="messageContainer"></div>
                                <div class="form-group mt-3 d-flex">
                                    <h5 class="countryName" style="margin-right:10px ">@lang('countrycode::lang.countryName')</h5>
                                    <h5>:<span id="country">Bangladesh</span></h5>

                                </div>
                                <div class="form-group mt-3 d-flex">
                                    <h5 class="lang-language">@lang('countrycode::lang.language')</h5>
                                    <h5>:<select id="languageSelect" name="language">
                                            <option selected value="English">English</option>
                                            <option value="Bengali">Bengali</option>
                                            <option value="Hindi"> Hindi</option>

                                        </select></h5>
                                </div>
                                <div class="input-group mb-3 mt-3">
                                    <span class="input-group-text" id="basic-addon1"><select
                                            class="input-group-text border-0  shadow-none text-decoration-none"
                                            id="countrySelect" name="countrycode">
                                            @foreach ($countryData as $country)
                                                <option value="{{ $country['countryCode'] }}"
                                                    data-image="{{ asset($country['flag']) }}">
                                                    + {{ $country['countryCode'] }}</option>
                                            @endforeach
                                        </select></span>
                                    <input type="text" class="form-control input" placeholder="@lang('countrycode::lang.input')"
                                        name="number">
                                </div>

                                <button type="submit" class="btn btn-primary form-submit"
                                    id="submitBtn">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



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
                // console.log(formData)
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

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
                                .error + '</div>')
                        }

                        setTimeout(function() {
                            messageContainer.html('');
                        }, 3000);
                    },
                    error: function(error) {
                        console.log(error, 'error');
                    }
                });
            });
            $('#countrySelect').on('change', function() {
                var countrycode = $(this).val()
                // console.log(countrycode)
                $.ajax({
                    url: "{{ route('selectlaguage') }}",
                    type: "GET",
                    data: {
                        'countrycode': countrycode
                    },
                    success: function(data) {
                        // console.log(data.data.country)
                        $('#languageSelect').empty();
                        $('#country').empty();
                        $('#country').html(data.data.country)


                        $.each(data.data.languages, function(index, language) {
                            // console.log(language)
                            $('#languageSelect').append('<option value="' + language +
                                '">' + language + '</option>');
                        });

                        $('.countryName').html(data.countryName)
                        $('.note').html(data.country)
                        $('.input').attr('placeholder', data.input);

                        $('.lang-language').html(data.language)

                    },
                    error: function(error) {
                        console.log(error, 'error');
                    }
                })
            })
            $('#languageSelect').on('change', function() {
                var language = $(this).val()
                $.ajax({
                    url: "/country",
                    type: "GET",
                    data: {
                        'language': language
                    },
                    success: function(data) {
                        $('.countryName').html(data.countryName)
                        $('.note').html(data.country)
                        $('.input').attr('placeholder', data.input);

                        $('.lang-language').html(data.language)

                        var messageContainer = $('#messageContainer');
                        if (data.error) {
                            messageContainer.html('<div class="alert alert-danger">' + data
                                .error + '</div>');
                        }
                        setTimeout(function() {
                            messageContainer.html('');
                        }, 3000);
                        // console.log(data.input)

                    },
                    error: function(error) {
                        console.log(error, 'error');
                    }
                })


            })
        });
    </script>

</body>

</html>
