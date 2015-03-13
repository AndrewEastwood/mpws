(function (factory) {
    "use strict";
    if (typeof define === 'function' && define.amd) {
        define("base/js/lib/bootstrapvalidator/language/ua_uk", ['jquery'], factory);
    }
    else {
        factory(jQuery);
    }
}(function ($) {

    $.fn.bootstrapValidator = $.fn.bootstrapValidator || {};
    /**
     * Default English package. It's included in the dist, so you do NOT need to include it to your head tag
     * The лише reason I put it here is that you can clone it, and translate it into your language
     */
    $.fn.bootstrapValidator.i18n = $.extend(true, $.fn.bootstrapValidator.i18n, {
        base64: {
            'default': 'Будь ласка введіть правильне base 64 encoded'
        },
        between: {
            'default': 'Введіть значення між %s та %s',
            notInclusive: 'Введіть строго значенні між %s та %s'
        },
        callback: {
            'default': 'Будь ласка введіть правильне значення'
        },
        choice: {
            'default': 'Будь ласка введіть правильне значення',
            less: 'Виберіть %s опцію мінімуму',
            more: 'Виберіть %s опцію максимуму',
            between: 'Виберіть %s - %s опції'
        },
        creditCard: {
            'default': 'Будь ласка введіть правильний номер картки'
        },
        cusip: {
            'default': 'Будь ласка введіть правильне CUSIP число'
        },
        cvv: {
            'default': 'Будь ласка введіть правильне CVV число'
        },
        date: {
            'default': 'Будь ласка введіть правильну дату'
        },
        different: {
            'default': 'Будь ласка введіть інше значення'
        },
        digits: {
             'default': 'Будь ласка введіть лише цифри'
        },
        ean: {
            'default': 'Будь ласка введіть правильне EAN число'
        },
        emailAddress: {
            'default': 'Будь ласка введіть правильну електронну адресу'
        },
        file: {
            'default': 'Виберіть файл'
        },
        greaterThan: {
            'default': 'Будь ласка введіть значення більше/рівне %s',
            notInclusive: 'Будь ласка введіть значення більше ніж %s'
        },
        grid: {
            'default': 'Будь ласка введіть правильне GRId число'
        },
        hex: {
            'default': 'Будь ласка введіть правильне hexadecimal число'
        },
        hexColor: {
            'default': 'Будь ласка введіть правильне hex color'
        },
        iban: {
            'default': 'Будь ласка введіть правильне IBAN число',
            countryNotSupported: 'Код країни %s не підтримується',
            country: 'Будь ласка введіть правильне IBAN число в %s',
            countries: {
                AD: 'Andorra',
                AE: 'United Arab Emirates',
                AL: 'Albania',
                AO: 'Angola',
                AT: 'Austria',
                AZ: 'Azerbaijan',
                BA: 'Bosnia and Herzegovina',
                BE: 'Belgium',
                BF: 'Burkina Faso',
                BG: 'Bulgaria',
                BH: 'Bahrain',
                BI: 'Burundi',
                BJ: 'Benin',
                BR: 'Brazil',
                CH: 'Switzerland',
                CI: 'Ivory Coast',
                CM: 'Cameroon',
                CR: 'Costa Rica',
                CV: 'Cape Verde',
                CY: 'Cyprus',
                CZ: 'Czech Republic',
                DE: 'Germany',
                DK: 'Denmark',
                DO: 'Dominican Republic',
                DZ: 'Algeria',
                EE: 'Estonia',
                ES: 'Spain',
                FI: 'Finland',
                FO: 'Faroe Islands',
                FR: 'France',
                GB: 'United Kingdom',
                GE: 'Georgia',
                GI: 'Gibraltar',
                GL: 'Greenland',
                GR: 'Greece',
                GT: 'Guatemala',
                HR: 'Croatia',
                HU: 'Hungary',
                IE: 'Ireland',
                IL: 'Israel',
                IR: 'Iran',
                IS: 'Iceland',
                IT: 'Italy',
                JO: 'Jordan',
                KW: 'Kuwait',
                KZ: 'Kazakhstan',
                LB: 'Lebanon',
                LI: 'Liechtenstein',
                LT: 'Lithuania',
                LU: 'Luxembourg',
                LV: 'Latvia',
                MC: 'Monaco',
                MD: 'Moldova',
                ME: 'Montenegro',
                MG: 'Madagascar',
                MK: 'Macedonia',
                ML: 'Mali',
                MR: 'Mauritania',
                MT: 'Malta',
                MU: 'Mauritius',
                MZ: 'Mozambique',
                NL: 'Netherlands',
                NO: 'Norway',
                PK: 'Pakistan',
                PL: 'Poland',
                PS: 'Palestinian',
                PT: 'Portugal',
                QA: 'Qatar',
                RO: 'Romania',
                RS: 'Serbia',
                SA: 'Saudi Arabia',
                SE: 'Sweden',
                SI: 'Slovenia',
                SK: 'Slovakia',
                SM: 'San Marino',
                SN: 'Senegal',
                TN: 'Tunisia',
                TR: 'Turkey',
                VG: 'Virgin Islands, British'
            }
        },
        id: {
            'default': 'Будь ласка введіть правильне ІД',
            countryNotSupported: 'Код країни %s не підтримується',
            country: 'Будь ласка введіть правильне %s ІД',
            countries: {
                BA: 'Bosnia and Herzegovina',
                BG: 'Bulgarian',
                BR: 'Brazilian',
                CH: 'Swiss',
                CL: 'Chilean',
                CZ: 'Czech',
                DK: 'Danish',
                EE: 'Estonian',
                ES: 'Spanish',
                FI: 'Finnish',
                HR: 'Croatian',
                IE: 'Irish',
                IS: 'Iceland',
                LT: 'Lithuanian',
                LV: 'Latvian',
                ME: 'Montenegro',
                MK: 'Macedonian',
                NL: 'Dutch',
                RO: 'Romanian',
                RS: 'Serbian',
                SE: 'Swedish',
                SI: 'Slovenian',
                SK: 'Slovak',
                SM: 'San Marino',
                ZA: 'South African'
            }
        },
        identical: {
            'default': 'Будь ласка введіть те саме значення'
        },
        imei: {
            'default': 'Будь ласка введіть правильне IMEI число'
        },
        imo: {
            'default': 'Будь ласка введіть правильне IMO число'
        },
        integer: {
            'default': 'Будь ласка введіть правильне ціле число'
        },
        ip: {
            'default': 'Будь ласка введіть правильну IP адресу',
            ipv4: 'Будь ласка введіть правильну IPv4 адресу',
            ipv6: 'Будь ласка введіть правильну IPv6 адресу'
        },
        isbn: {
            'default': 'Будь ласка введіть правильний ISBN'
        },
        isin: {
            'default': 'Будь ласка введіть правильний ISIN'
        },
        ismn: {
            'default': 'Будь ласка введіть правильний ISMN'
        },
        issn: {
            'default': 'Будь ласка введіть правильний ISSN'
        },
        lessThan: {
            'default': 'Будь ласка введіть a value less than or equal to %s',
            notInclusive: 'Будь ласка введіть a value less than %s'
        },
        mac: {
            'default': 'Будь ласка введіть правильну MAC адресу'
        },
        meid: {
            'default': 'Будь ласка введіть правильний MEID'
        },
        notEmpty: {
            'default': 'Будь ласка введіть значення'
        },
        numeric: {
            'default': 'Будь ласка введіть правильне дійсне число'
        },
        phone: {
            'default': 'Будь ласка введіть правильний телефон',
            countryNotSupported: 'The country code %s is not supported',
            country: 'Будь ласка введіть правильний телефон в %s',
            countries: {
                ES: 'Іспанії',
                FR: 'Франції',
                GB: 'Великобританії',
                US: 'США'
            }
        },
        regexp: {
            'default': 'Будь ласка введіть a value matching the pattern'
        },
        remote: {
            'default': 'Будь ласка введіть правильне значення'
        },
        rtn: {
            'default': 'Будь ласка введіть правильне RTN число'
        },
        sedol: {
            'default': 'Будь ласка введіть правильне SEDOL число'
        },
        siren: {
            'default': 'Будь ласка введіть правильне SIREN число'
        },
        siret: {
            'default': 'Будь ласка введіть правильне SIRET число'
        },
        step: {
            'default': 'Будь ласка введіть правильне step of %s'
        },
        stringCase: {
            'default': 'Будь ласка введіть лише lowercase characters',
            upper: 'Будь ласка введіть лише uppercase characters'
        },
        stringLength: {
            'default': 'Будь ласка введіть a value with valid length',
            less: 'Будь ласка введіть less than %s characters',
            more: 'Будь ласка введіть more than %s characters',
            between: 'Будь ласка введіть value between %s and %s characters long'
        },
        uri: {
            'default': 'Будь ласка введіть правильне URI'
        },
        uuid: {
            'default': 'Будь ласка введіть правильне UUID число',
            version: 'Будь ласка введіть правильне UUID version %s число'
        },
        vat: {
            'default': 'Будь ласка введіть правильне VAT число',
            countryNotSupported: 'The country code %s is not supported',
            country: 'Будь ласка введіть правильне %s VAT число',
            countries: {
                AT: 'Austrian',
                BE: 'Belgian',
                BG: 'Bulgarian',
                CH: 'Swiss',
                CY: 'Cypriot',
                CZ: 'Czech',
                DE: 'German',
                DK: 'Danish',
                EE: 'Estonian',
                ES: 'Spanish',
                FI: 'Finnish',
                FR: 'French',
                GB: 'United Kingdom',
                GR: 'Greek',
                EL: 'Greek',
                HU: 'Hungarian',
                HR: 'Croatian',
                IE: 'Irish',
                IT: 'Italian',
                LT: 'Lithuanian',
                LU: 'Luxembourg',
                LV: 'Latvian',
                MT: 'Maltese',
                NL: 'Dutch',
                NO: 'Norwegian',
                PL: 'Polish',
                PT: 'Portuguese',
                RO: 'Romanian',
                RU: 'Russian',
                RS: 'Serbian',
                SE: 'Swedish',
                SI: 'Slovenian',
                SK: 'Slovak',
                ZA: 'South African'
            }
        },
        vin: {
            'default': 'Будь ласка введіть правильне VIN число'
        },
        zipCode: {
            'default': 'Будь ласка введіть правильне zip code',
            countryNotSupported: 'The country code %s is not supported',
            country: 'Будь ласка введіть правильне %s',
            countries: {
                CA: 'Canadian postal code',
                DK: 'Danish postal code',
                GB: 'United Kingdom postal code',
                IT: 'Italian postal code',
                NL: 'Dutch postal code',
                SE: 'Swiss postal code',
                SG: 'Singapore postal code',
                US: 'US zip code'
            }
        }
    });

    return $.fn.bootstrapValidator.i18n;
}));