(function() {
'use strict';
angular.module('resortpro.property')
    .directive('compileTemplate', function($compile, $parse){
        return {
            link: function(scope, element, attr){
                var parsed = $parse(attr.ngBindHtml);
                function getStringValue() { return (parsed(scope) || '').toString(); }
                scope.$watch(getStringValue, function() {
                    $compile(element, null, -9999)(scope);  //The -9999 makes it skip directives so that we do not recompile ourselves
                });
            }
        }
    })
    .controller('PropertyCheckoutController', [ '$scope', 'rpapi', '$sce', '$timeout', '$http', '$compile',
        function ($scope, rpapi, $sce, $timeout, $http ,$compile) {
        $scope.checkoutErrorMessages = [];
        $scope.checkoutCountries = [
            {
                "name": "Afghanistan",
                "code": "AF"
            },
            {
                "name": "Åland Islands",
                "code": "AX"
            },
            {
                "name": "Albania",
                "code": "AL"
            },
            {
                "name": "Algeria",
                "code": "DZ"
            },
            {
                "name": "American Samoa",
                "code": "AS"
            },
            {
                "name": "AndorrA",
                "code": "AD"
            },
            {
                "name": "Angola",
                "code": "AO"
            },
            {
                "name": "Anguilla",
                "code": "AI"
            },
            {
                "name": "Antarctica",
                "code": "AQ"
            },
            {
                "name": "Antigua and Barbuda",
                "code": "AG"
            },
            {
                "name": "Argentina",
                "code": "AR"
            },
            {
                "name": "Armenia",
                "code": "AM"
            },
            {
                "name": "Aruba",
                "code": "AW"
            },
            {
                "name": "Australia",
                "code": "AU"
            },
            {
                "name": "Austria",
                "code": "AT"
            },
            {
                "name": "Azerbaijan",
                "code": "AZ"
            },
            {
                "name": "Bahamas",
                "code": "BS"
            },
            {
                "name": "Bahrain",
                "code": "BH"
            },
            {
                "name": "Bangladesh",
                "code": "BD"
            },
            {
                "name": "Barbados",
                "code": "BB"
            },
            {
                "name": "Belarus",
                "code": "BY"
            },
            {
                "name": "Belgium",
                "code": "BE"
            },
            {
                "name": "Belize",
                "code": "BZ"
            },
            {
                "name": "Benin",
                "code": "BJ"
            },
            {
                "name": "Bermuda",
                "code": "BM"
            },
            {
                "name": "Bhutan",
                "code": "BT"
            },
            {
                "name": "Bolivia",
                "code": "BO"
            },
            {
                "name": "Bosnia and Herzegovina",
                "code": "BA"
            },
            {
                "name": "Botswana",
                "code": "BW"
            },
            {
                "name": "Bouvet Island",
                "code": "BV"
            },
            {
                "name": "Brazil",
                "code": "BR"
            },
            {
                "name": "British Indian Ocean Territory",
                "code": "IO"
            },
            {
                "name": "Brunei Darussalam",
                "code": "BN"
            },
            {
                "name": "Bulgaria",
                "code": "BG"
            },
            {
                "name": "Burkina Faso",
                "code": "BF"
            },
            {
                "name": "Burundi",
                "code": "BI"
            },
            {
                "name": "Cambodia",
                "code": "KH"
            },
            {
                "name": "Cameroon",
                "code": "CM"
            },
            {
                "name": "Canada",
                "code": "CA"
            },
            {
                "name": "Cape Verde",
                "code": "CV"
            },
            {
                "name": "Cayman Islands",
                "code": "KY"
            },
            {
                "name": "Central African Republic",
                "code": "CF"
            },
            {
                "name": "Chad",
                "code": "TD"
            },
            {
                "name": "Chile",
                "code": "CL"
            },
            {
                "name": "China",
                "code": "CN"
            },
            {
                "name": "Christmas Island",
                "code": "CX"
            },
            {
                "name": "Cocos (Keeling) Islands",
                "code": "CC"
            },
            {
                "name": "Colombia",
                "code": "CO"
            },
            {
                "name": "Comoros",
                "code": "KM"
            },
            {
                "name": "Congo",
                "code": "CG"
            },
            {
                "name": "Congo, Democratic Republic",
                "code": "CD"
            },
            {
                "name": "Cook Islands",
                "code": "CK"
            },
            {
                "name": "Costa Rica",
                "code": "CR"
            },
            {
                "name": "Cote D\"Ivoire",
                "code": "CI"
            },
            {
                "name": "Croatia",
                "code": "HR"
            },
            {
                "name": "Cuba",
                "code": "CU"
            },
            {
                "name": "Cyprus",
                "code": "CY"
            },
            {
                "name": "Czech Republic",
                "code": "CZ"
            },
            {
                "name": "Denmark",
                "code": "DK"
            },
            {
                "name": "Djibouti",
                "code": "DJ"
            },
            {
                "name": "Dominica",
                "code": "DM"
            },
            {
                "name": "Dominican Republic",
                "code": "DO"
            },
            {
                "name": "Ecuador",
                "code": "EC"
            },
            {
                "name": "Egypt",
                "code": "EG"
            },
            {
                "name": "El Salvador",
                "code": "SV"
            },
            {
                "name": "Equatorial Guinea",
                "code": "GQ"
            },
            {
                "name": "Eritrea",
                "code": "ER"
            },
            {
                "name": "Estonia",
                "code": "EE"
            },
            {
                "name": "Ethiopia",
                "code": "ET"
            },
            {
                "name": "Falkland Islands (Malvinas)",
                "code": "FK"
            },
            {
                "name": "Faroe Islands",
                "code": "FO"
            },
            {
                "name": "Fiji",
                "code": "FJ"
            },
            {
                "name": "Finland",
                "code": "FI"
            },
            {
                "name": "France",
                "code": "FR"
            },
            {
                "name": "French Guiana",
                "code": "GF"
            },
            {
                "name": "French Polynesia",
                "code": "PF"
            },
            {
                "name": "French Southern Territories",
                "code": "TF"
            },
            {
                "name": "Gabon",
                "code": "GA"
            },
            {
                "name": "Gambia",
                "code": "GM"
            },
            {
                "name": "Georgia",
                "code": "GE"
            },
            {
                "name": "Germany",
                "code": "DE"
            },
            {
                "name": "Ghana",
                "code": "GH"
            },
            {
                "name": "Gibraltar",
                "code": "GI"
            },
            {
                "name": "Greece",
                "code": "GR"
            },
            {
                "name": "Greenland",
                "code": "GL"
            },
            {
                "name": "Grenada",
                "code": "GD"
            },
            {
                "name": "Guadeloupe",
                "code": "GP"
            },
            {
                "name": "Guam",
                "code": "GU"
            },
            {
                "name": "Guatemala",
                "code": "GT"
            },
            {
                "name": "Guernsey",
                "code": "GG"
            },
            {
                "name": "Guinea",
                "code": "GN"
            },
            {
                "name": "Guinea-Bissau",
                "code": "GW"
            },
            {
                "name": "Guyana",
                "code": "GY"
            },
            {
                "name": "Haiti",
                "code": "HT"
            },
            {
                "name": "Heard Island and Mcdonald Islands",
                "code": "HM"
            },
            {
                "name": "Holy See (Vatican City State)",
                "code": "VA"
            },
            {
                "name": "Honduras",
                "code": "HN"
            },
            {
                "name": "Hong Kong",
                "code": "HK"
            },
            {
                "name": "Hungary",
                "code": "HU"
            },
            {
                "name": "Iceland",
                "code": "IS"
            },
            {
                "name": "India",
                "code": "IN"
            },
            {
                "name": "Indonesia",
                "code": "ID"
            },
            {
                "name": "Iran",
                "code": "IR"
            },
            {
                "name": "Iraq",
                "code": "IQ"
            },
            {
                "name": "Ireland",
                "code": "IE"
            },
            {
                "name": "Isle of Man",
                "code": "IM"
            },
            {
                "name": "Israel",
                "code": "IL"
            },
            {
                "name": "Italy",
                "code": "IT"
            },
            {
                "name": "Jamaica",
                "code": "JM"
            },
            {
                "name": "Japan",
                "code": "JP"
            },
            {
                "name": "Jersey",
                "code": "JE"
            },
            {
                "name": "Jordan",
                "code": "JO"
            },
            {
                "name": "Kazakhstan",
                "code": "KZ"
            },
            {
                "name": "Kenya",
                "code": "KE"
            },
            {
                "name": "Kiribati",
                "code": "KI"
            },
            {
                "name": "Korea (North)",
                "code": "KP"
            },
            {
                "name": "Korea (South)",
                "code": "KR"
            },
            {
                "name": "Kosovo",
                "code": "XK"
            },
            {
                "name": "Kuwait",
                "code": "KW"
            },
            {
                "name": "Kyrgyzstan",
                "code": "KG"
            },
            {
                "name": "Laos",
                "code": "LA"
            },
            {
                "name": "Latvia",
                "code": "LV"
            },
            {
                "name": "Lebanon",
                "code": "LB"
            },
            {
                "name": "Lesotho",
                "code": "LS"
            },
            {
                "name": "Liberia",
                "code": "LR"
            },
            {
                "name": "Libyan Arab Jamahiriya",
                "code": "LY"
            },
            {
                "name": "Liechtenstein",
                "code": "LI"
            },
            {
                "name": "Lithuania",
                "code": "LT"
            },
            {
                "name": "Luxembourg",
                "code": "LU"
            },
            {
                "name": "Macao",
                "code": "MO"
            },
            {
                "name": "Macedonia",
                "code": "MK"
            },
            {
                "name": "Madagascar",
                "code": "MG"
            },
            {
                "name": "Malawi",
                "code": "MW"
            },
            {
                "name": "Malaysia",
                "code": "MY"
            },
            {
                "name": "Maldives",
                "code": "MV"
            },
            {
                "name": "Mali",
                "code": "ML"
            },
            {
                "name": "Malta",
                "code": "MT"
            },
            {
                "name": "Marshall Islands",
                "code": "MH"
            },
            {
                "name": "Martinique",
                "code": "MQ"
            },
            {
                "name": "Mauritania",
                "code": "MR"
            },
            {
                "name": "Mauritius",
                "code": "MU"
            },
            {
                "name": "Mayotte",
                "code": "YT"
            },
            {
                "name": "Mexico",
                "code": "MX"
            },
            {
                "name": "Micronesia",
                "code": "FM"
            },
            {
                "name": "Moldova",
                "code": "MD"
            },
            {
                "name": "Monaco",
                "code": "MC"
            },
            {
                "name": "Mongolia",
                "code": "MN"
            },
            {
                "name": "Montserrat",
                "code": "MS"
            },
            {
                "name": "Morocco",
                "code": "MA"
            },
            {
                "name": "Mozambique",
                "code": "MZ"
            },
            {
                "name": "Myanmar",
                "code": "MM"
            },
            {
                "name": "Namibia",
                "code": "NA"
            },
            {
                "name": "Nauru",
                "code": "NR"
            },
            {
                "name": "Nepal",
                "code": "NP"
            },
            {
                "name": "Netherlands",
                "code": "NL"
            },
            {
                "name": "Netherlands Antilles",
                "code": "AN"
            },
            {
                "name": "New Caledonia",
                "code": "NC"
            },
            {
                "name": "New Zealand",
                "code": "NZ"
            },
            {
                "name": "Nicaragua",
                "code": "NI"
            },
            {
                "name": "Niger",
                "code": "NE"
            },
            {
                "name": "Nigeria",
                "code": "NG"
            },
            {
                "name": "Niue",
                "code": "NU"
            },
            {
                "name": "Norfolk Island",
                "code": "NF"
            },
            {
                "name": "Northern Mariana Islands",
                "code": "MP"
            },
            {
                "name": "Norway",
                "code": "NO"
            },
            {
                "name": "Oman",
                "code": "OM"
            },
            {
                "name": "Pakistan",
                "code": "PK"
            },
            {
                "name": "Palau",
                "code": "PW"
            },
            {
                "name": "Palestinian Territory, Occupied",
                "code": "PS"
            },
            {
                "name": "Panama",
                "code": "PA"
            },
            {
                "name": "Papua New Guinea",
                "code": "PG"
            },
            {
                "name": "Paraguay",
                "code": "PY"
            },
            {
                "name": "Peru",
                "code": "PE"
            },
            {
                "name": "Philippines",
                "code": "PH"
            },
            {
                "name": "Pitcairn",
                "code": "PN"
            },
            {
                "name": "Poland",
                "code": "PL"
            },
            {
                "name": "Portugal",
                "code": "PT"
            },
            {
                "name": "Puerto Rico",
                "code": "PR"
            },
            {
                "name": "Qatar",
                "code": "QA"
            },
            {
                "name": "Reunion",
                "code": "RE"
            },
            {
                "name": "Romania",
                "code": "RO"
            },
            {
                "name": "Russian Federation",
                "code": "RU"
            },
            {
                "name": "RWANDA",
                "code": "RW"
            },
            {
                "name": "Saint Helena",
                "code": "SH"
            },
            {
                "name": "Saint Kitts and Nevis",
                "code": "KN"
            },
            {
                "name": "Saint Lucia",
                "code": "LC"
            },
            {
                "name": "Saint Pierre and Miquelon",
                "code": "PM"
            },
            {
                "name": "Saint Vincent and the Grenadines",
                "code": "VC"
            },
            {
                "name": "Samoa",
                "code": "WS"
            },
            {
                "name": "San Marino",
                "code": "SM"
            },
            {
                "name": "Sao Tome and Principe",
                "code": "ST"
            },
            {
                "name": "Saudi Arabia",
                "code": "SA"
            },
            {
                "name": "Senegal",
                "code": "SN"
            },
            {
                "name": "Serbia",
                "code": "RS"
            },
            {
                "name": "Montenegro",
                "code": "ME"
            },
            {
                "name": "Seychelles",
                "code": "SC"
            },
            {
                "name": "Sierra Leone",
                "code": "SL"
            },
            {
                "name": "Singapore",
                "code": "SG"
            },
            {
                "name": "Slovakia",
                "code": "SK"
            },
            {
                "name": "Slovenia",
                "code": "SI"
            },
            {
                "name": "Solomon Islands",
                "code": "SB"
            },
            {
                "name": "Somalia",
                "code": "SO"
            },
            {
                "name": "South Africa",
                "code": "ZA"
            },
            {
                "name": "South Georgia and the South Sandwich Islands",
                "code": "GS"
            },
            {
                "name": "Spain",
                "code": "ES"
            },
            {
                "name": "Sri Lanka",
                "code": "LK"
            },
            {
                "name": "Sudan",
                "code": "SD"
            },
            {
                "name": "Suriname",
                "code": "SR"
            },
            {
                "name": "Svalbard and Jan Mayen",
                "code": "SJ"
            },
            {
                "name": "Swaziland",
                "code": "SZ"
            },
            {
                "name": "Sweden",
                "code": "SE"
            },
            {
                "name": "Switzerland",
                "code": "CH"
            },
            {
                "name": "Syrian Arab Republic",
                "code": "SY"
            },
            {
                "name": "Taiwan, Province of China",
                "code": "TW"
            },
            {
                "name": "Tajikistan",
                "code": "TJ"
            },
            {
                "name": "Tanzania",
                "code": "TZ"
            },
            {
                "name": "Thailand",
                "code": "TH"
            },
            {
                "name": "Timor-Leste",
                "code": "TL"
            },
            {
                "name": "Togo",
                "code": "TG"
            },
            {
                "name": "Tokelau",
                "code": "TK"
            },
            {
                "name": "Tonga",
                "code": "TO"
            },
            {
                "name": "Trinidad and Tobago",
                "code": "TT"
            },
            {
                "name": "Tunisia",
                "code": "TN"
            },
            {
                "name": "Turkey",
                "code": "TR"
            },
            {
                "name": "Turkmenistan",
                "code": "TM"
            },
            {
                "name": "Turks and Caicos Islands",
                "code": "TC"
            },
            {
                "name": "Tuvalu",
                "code": "TV"
            },
            {
                "name": "Uganda",
                "code": "UG"
            },
            {
                "name": "Ukraine",
                "code": "UA"
            },
            {
                "name": "United Arab Emirates",
                "code": "AE"
            },
            {
                "name": "United Kingdom",
                "code": "GB"
            },
            {
                "name": "United States",
                "code": "US"
            },
            {
                "name": "United States Minor Outlying Islands",
                "code": "UM"
            },
            {
                "name": "Uruguay",
                "code": "UY"
            },
            {
                "name": "Uzbekistan",
                "code": "UZ"
            },
            {
                "name": "Vanuatu",
                "code": "VU"
            },
            {
                "name": "Venezuela",
                "code": "VE"
            },
            {
                "name": "Viet Nam",
                "code": "VN"
            },
            {
                "name": "Virgin Islands, British",
                "code": "VG"
            },
            {
                "name": "Virgin Islands, U.S.",
                "code": "VI"
            },
            {
                "name": "Wallis and Futuna",
                "code": "WF"
            },
            {
                "name": "Western Sahara",
                "code": "EH"
            },
            {
                "name": "Yemen",
                "code": "YE"
            },
            {
                "name": "Zambia",
                "code": "ZM"
            },
            {
                "name": "Zimbabwe",
                "code": "ZW"
            }
        ];
        $scope.stateDictionaryUSA = [
            {
                "name": "Alabama",
                "abbreviation": "AL"
            },
            {
                "name": "Alaska",
                "abbreviation": "AK"
            },
            {
                "name": "American Samoa",
                "abbreviation": "AS"
            },
            {
                "name": "Arizona",
                "abbreviation": "AZ"
            },
            {
                "name": "Arkansas",
                "abbreviation": "AR"
            },
            {
                "name": "California",
                "abbreviation": "CA"
            },
            {
                "name": "Colorado",
                "abbreviation": "CO"
            },
            {
                "name": "Connecticut",
                "abbreviation": "CT"
            },
            {
                "name": "Delaware",
                "abbreviation": "DE"
            },
            {
                "name": "District Of Columbia",
                "abbreviation": "DC"
            },
            {
                "name": "Federated States Of Micronesia",
                "abbreviation": "FM"
            },
            {
                "name": "Florida",
                "abbreviation": "FL"
            },
            {
                "name": "Georgia",
                "abbreviation": "GA"
            },
            {
                "name": "Guam",
                "abbreviation": "GU"
            },
            {
                "name": "Hawaii",
                "abbreviation": "HI"
            },
            {
                "name": "Idaho",
                "abbreviation": "ID"
            },
            {
                "name": "Illinois",
                "abbreviation": "IL"
            },
            {
                "name": "Indiana",
                "abbreviation": "IN"
            },
            {
                "name": "Iowa",
                "abbreviation": "IA"
            },
            {
                "name": "Kansas",
                "abbreviation": "KS"
            },
            {
                "name": "Kentucky",
                "abbreviation": "KY"
            },
            {
                "name": "Louisiana",
                "abbreviation": "LA"
            },
            {
                "name": "Maine",
                "abbreviation": "ME"
            },
            {
                "name": "Marshall Islands",
                "abbreviation": "MH"
            },
            {
                "name": "Maryland",
                "abbreviation": "MD"
            },
            {
                "name": "Massachusetts",
                "abbreviation": "MA"
            },
            {
                "name": "Michigan",
                "abbreviation": "MI"
            },
            {
                "name": "Minnesota",
                "abbreviation": "MN"
            },
            {
                "name": "Mississippi",
                "abbreviation": "MS"
            },
            {
                "name": "Missouri",
                "abbreviation": "MO"
            },
            {
                "name": "Montana",
                "abbreviation": "MT"
            },
            {
                "name": "Nebraska",
                "abbreviation": "NE"
            },
            {
                "name": "Nevada",
                "abbreviation": "NV"
            },
            {
                "name": "New Hampshire",
                "abbreviation": "NH"
            },
            {
                "name": "New Jersey",
                "abbreviation": "NJ"
            },
            {
                "name": "New Mexico",
                "abbreviation": "NM"
            },
            {
                "name": "New York",
                "abbreviation": "NY"
            },
            {
                "name": "North Carolina",
                "abbreviation": "NC"
            },
            {
                "name": "North Dakota",
                "abbreviation": "ND"
            },
            {
                "name": "Northern Mariana Islands",
                "abbreviation": "MP"
            },
            {
                "name": "Ohio",
                "abbreviation": "OH"
            },
            {
                "name": "Oklahoma",
                "abbreviation": "OK"
            },
            {
                "name": "Oregon",
                "abbreviation": "OR"
            },
            {
                "name": "Palau",
                "abbreviation": "PW"
            },
            {
                "name": "Pennsylvania",
                "abbreviation": "PA"
            },
            {
                "name": "Puerto Rico",
                "abbreviation": "PR"
            },
            {
                "name": "Rhode Island",
                "abbreviation": "RI"
            },
            {
                "name": "South Carolina",
                "abbreviation": "SC"
            },
            {
                "name": "South Dakota",
                "abbreviation": "SD"
            },
            {
                "name": "Tennessee",
                "abbreviation": "TN"
            },
            {
                "name": "Texas",
                "abbreviation": "TX"
            },
            {
                "name": "Utah",
                "abbreviation": "UT"
            },
            {
                "name": "Vermont",
                "abbreviation": "VT"
            },
            {
                "name": "Virgin Islands",
                "abbreviation": "VI"
            },
            {
                "name": "Virginia",
                "abbreviation": "VA"
            },
            {
                "name": "Washington",
                "abbreviation": "WA"
            },
            {
                "name": "West Virginia",
                "abbreviation": "WV"
            },
            {
                "name": "Wisconsin",
                "abbreviation": "WI"
            },
            {
                "name": "Wyoming",
                "abbreviation": "WY"
            }
        ];

        $scope.cardTypes = [
            {
                id: 1,
                value: "Visa"
            },
            {
                id: 2,
                value: "MasterCard"
            },
            {
                id: 3,
                value: "AmericanExpress"
            },
            {
                id: 4,
                value: "Discover"
            }
        ];

        $scope.submitCheckout = function () {
            $scope.checkoutSpinner(true);
            rpapi.getWithParams('GetPreReservationPrice', {
                'unit_id': $scope.book.unit_id,
                'startdate': $scope.book.checkin,
                'enddate': $scope.book.checkout,
                'occupants': $scope.book.occupants,
                'occupants_small' : $scope.book.occupants_small,
                'pets' : $scope.book.pets,
                'coupon_code' : $scope.couponCode
            }).success(function (obj) {
                if (obj.data !== undefined) {

                    var prepearedMakeReservationData = {
                        'unit_id': $scope.book.unit_id || '',
                        'startdate': $scope.book.checkin || '',
                        'enddate': $scope.book.checkout || '',
                        'occupants': $scope.book.occupants || '',
                        'occupants_small' : $scope.book.occupants_small || '',
                        'pets' : $scope.book.pets || '',
                        'coupon_code' : $scope.couponCode || '',
                        "first_name": $scope.user.firstName || '',
                        "last_name": $scope.user.lastName || '',
                        "address": $scope.user.adress || '',
                        "city": $scope.user.city || '',
                        "state_name": $scope.user.state || '',
                        "zip": $scope.user.postalCode || '',
                        "email": $scope.user.email || '',
                        "client_comments": $scope.user.comments || '',
                        "country_name": $scope.user.country || '',
                        "credit_card_type_id": $scope.user.cardType || '',
                        "credit_card_number": $scope.user.cardNumber || '',
                        "credit_card_cid": $scope.user.svv || '',
                        "credit_card_expiration_month": $scope.user.expMonth || '',
                        "credit_card_expiration_year": $scope.user.expYear || '',
                        "credit_card_amount": $scope.total_reservation || ''
                    };

                    // Send optional fees count data, but API doesn't process yet.
                    angular.forEach($scope.optional_fees, function (fee) {
                        if(fee.count > 0){
                            var key = 'optional_fee_' + fee.id;
                            prepearedMakeReservationData[key] = fee.count;
                        }
                    });

                    rpapi.getWithParams('MakeReservation', prepearedMakeReservationData).success(function (obj) {

                        if (obj.status && obj.status.description){
                            $scope.checkoutErrorMessages.push({message : $sce.trustAsHtml(obj.status.description) });
                            $scope.clearCheckoutErrorMessage();
                        }

                        if (obj.data && obj.data.reservation && obj.data.reservation.confirmation_id) {
                            try{
                                $http.get($scope.listIncludePages.checkoutSuccessTemplateDestination).then(function (response) {
                                    if (response.statusText === 'OK') {
                                        $scope.successCheckoutData = obj.data.reservation;
                                        delete $scope.successCheckoutData.travelagent_name; // Always empty obj

                                        var compiledeHTML = $compile(response.data)($scope);

                                        jQuery("#single-room")
                                            .hide(0)
                                            .empty()
                                            .append(compiledeHTML)
                                            .fadeIn(600);
                                    }
                                });
                            }catch(e){
                                console.log('ERROR: Cant include checkout success template!');
                            }
                        }
                        $scope.checkoutSpinner(false);
                    })
                } else {
                    $scope.checkoutErrorMessages.push({message : 'Server does not respond, try again later or contact the administrator.'});
                    $scope.clearCheckoutErrorMessage();
                    $scope.checkoutSpinner(false);
                }
            });
        };

        $scope.clearCheckoutErrorMessage = function () {
            $timeout( function(){
                $scope.checkoutErrorMessages.shift();
            }, 15000 );
        };

        $scope.checkoutSpinner = function (status) {
            if (status) {
                jQuery('.resortpro_unit_submit_blue_spinner').css('display', 'inline-block');
                jQuery('.resortpro_unit_submit_blue_message').css('display', 'none');
            } else {
                jQuery('.resortpro_unit_submit_blue_spinner').css('display', 'none');
                jQuery('.resortpro_unit_submit_blue_message').css('display', 'inline-block');
            }
        }
    }]);
})();
