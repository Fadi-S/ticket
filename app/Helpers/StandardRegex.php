<?php

namespace App\Helpers;

class StandardRegex {

    const NATIONAL_ID = '([23])(?:([0-9]{2})(?:(?:(0[13578]|1[02])(0[1-9]|[12][0-9]|3[01]))|(?:(0[469]|11)(0[1-9]|[12][0-9]|30))|(?:(02)(0[1-9]|1[0-9]|2[0-8])))|(?:(04|08|[2468][048]|[13579][26]|(?<=3)00)(02)(29)))(0[1-34]|[12][1-9]|3[1-5]|88)([0-9]{3}([0-9]))([0-9])';

    const PHONE_NUMBER = '((\+2|2)?01)[0-9]{9}';

}