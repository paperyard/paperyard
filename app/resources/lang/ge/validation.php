<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'Das Attribut: muss akzeptiert werden.',
    'active_url'           => 'Das Attribut: ist keine gültige URL.',
    'after'                => 'Das: Attribut muss ein Datum nach dem: Datum sein.',
    'after_or_equal'       => 'Das: Attribut muss ein Datum nach oder gleich dem Datum sein.',
    'alpha'                => 'Das Attribut: darf nur Buchstaben enthalten.',
    'alpha_dash'           => 'Das: -Attribut darf nur Buchstaben, Zahlen und Bindestriche enthalten.',
    'alpha_num'            => 'Das Attribut: darf nur Buchstaben und Zahlen enthalten.',
    'array'                => 'Das: Attribut muss ein Array sein.',
    'before'               => 'Das: Attribut muss ein Datum vorher sein: Datum.',
    'before_or_equal'      => 'Das: Attribut muss ein Datum vor oder gleich dem Datum sein.',
    'between'              => [
        'numeric' => 'Das: Attribut muss zwischen: min und: max. :Liegen',
        'file'    => 'Das: Attribut muss zwischen: min und: max Kilobyte liegen.',
        'string'  => 'Das: Attribut muss zwischen: min und: max Zeichen liegen.',
        'array'   => 'Das: Attribut muss zwischen: min und: max. Elemente haben.',
    ],
    'boolean'              => 'Das Attributfeld muss wahr oder falsch sein.',
    'confirmed'            => 'Die: Attributbestätigung stimmt nicht überein.',
    'date'                 => 'Das: Attribut ist kein gültiges Datum.',
    'date_format'          => 'Das: Attribut entspricht nicht dem Format: format.',
    'different'            => 'Das: Attribut und: Anderes muss anders sein.',
    'digits'               => 'Das: Attribut muss sein: Ziffern Ziffern.',
    'digits_between'       => 'Das: Attribut muss zwischen: min und: max digits liegen.',
    'dimensions'           => 'Das: -Attribut hat ungültige Bilddimensionen.',
    'distinct'             => 'Das: Attributfeld hat einen doppelten Wert.',
    'email'                => 'Das: -Attribut muss eine gültige E-Mail-Adresse sein.',
    'exists'               => 'Das Attribut selected: ist ungültig.',
    'file'                 => 'Das: Attribut muss eine Datei sein.',
    'filled'               => 'Das: Attributfeld muss einen Wert haben.',
    'gt'                   => [
        'numeric' => 'Das: Attribut muss größer sein als: Wert.',
        'file'    => 'Das: -Attribut muss größer sein als: Wert Kilobyte.',
        'string'  => 'Das: Attribut muss größer sein als: Wert Zeichen.',
        'array'   => 'Das: Attribut muss mehr als: Wertelemente haben.',
    ],
    'gte'                  => [
        'numeric' => 'Das: Attribut muss größer als oder gleich sein: Wert.',
        'file'    => 'Das: Attribut muss größer oder gleich sein: Wert Kilobyte.',
        'string'  => 'Das: Attribut muss größer oder gleich sein: Wert Zeichen.',
        'array'   => 'Das: -Attribut muss folgende Werte haben: Wertelemente oder mehr.',
    ],
    'image'                => 'Das: Attribut muss ein Bild sein.',
    'in'                   => 'Das Attribut selected: ist ungültig.',
    'in_array'             => 'Das Feld: attribute existiert nicht in: other.',
    'integer'              => 'Das: Attribut muss eine Ganzzahl sein.',
    'ip'                   => 'Das: Attribut muss eine gültige IP-Adresse sein.',
    'ipv4'                 => 'Das: -Attribut muss eine gültige IPv4-Adresse sein.',
    'ipv6'                 => 'Das: -Attribut muss eine gültige IPv6-Adresse sein.',
    'json'                 => 'Das: -Attribut muss eine gültige JSON-Zeichenfolge sein.',
    'lt'                   => [
        'numeric' => 'Das: Attribut muss kleiner sein als: Wert.',
        'file'    => 'Das: -Attribut muss kleiner sein als: Wert Kilobyte.',
        'string'  => 'Das: -Attribut muss kleiner sein als: Wertzeichen.',
        'array'   => 'Das: Attribut muss weniger als: Wertelemente haben.',
    ],
    'lte'                  => [
        'numeric' => 'Das: Attribut muss kleiner oder gleich sein: Wert.',
        'file'    => 'Das: Attribut muss kleiner oder gleich sein: Wert Kilobyte.',
        'string'  => 'Das: Attribut muss kleiner oder gleich sein: Wert Zeichen.',
        'array'   => 'Das: Attribut darf nicht mehr als: Wertgegenstände haben.',
    ],
    'max'                  => [
        'numeric' => 'Das: Attribut darf nicht größer sein als: max.',
        'file'    => 'Das: Attribut darf nicht größer sein als: max. Kilobyte.',
        'string'  => 'Das: Attribut darf nicht größer sein als: max. Zeichen.',
        'array'   => 'Das: Attribut darf nicht mehr als: max. Elemente haben.',
    ],
    'mimes'                => 'Das: Attribut muss eine Datei vom Typ: :values ​​sein.',
    'mimetypes'            => 'Das: Attribut muss eine Datei vom Typ:: values ​​sein.',
    'min'                  => [
        'numeric' => 'Das: Attribut muss mindestens sein: min.',
        'file'    => 'Das: Attribut muss mindestens: min Kilobytes sein.',
        'string'  => 'Das: Attribut muss mindestens: min. Zeichen sein.',
        'array'   => 'Das: Attribut muss mindestens: min. Elemente haben.',
    ],
    'not_in'               => 'Das Attribut selected: ist ungültig.',
    'not_regex'            => 'Das: Attributformat ist ungültig.',
    'numeric'              => 'Das: Attribut muss eine Zahl sein.',
    'present'              => 'Das Attributfeld muss vorhanden sein.',
    'regex'                => 'Das: Attributformat ist ungültig.',
    'required'             => 'Das Attributfeld ist erforderlich.',
    'required_if'          => 'Das Feld: attribute wird benötigt, wenn: other ist: value.',
    'required_unless'      => 'Das Attributfeld ist erforderlich, außer: other ist in: values.',
    'required_with'        => 'Das Attributfeld ist erforderlich, wenn: Werte vorhanden sind.',
    'required_with_all'    => 'Das Attributfeld ist erforderlich, wenn: Werte vorhanden sind.',
    'required_without'     => 'Das Attributfeld: ist erforderlich, wenn: Werte nicht vorhanden sind.',
    'required_without_all' => 'Das Attributfeld: ist erforderlich, wenn keine der folgenden Werte vorhanden ist:',
    'same'                 => 'Das: Attribut und: anderes muss übereinstimmen.',
    'size'                 => [
        'numeric' => 'Das: Attribut muss sein: Größe.',
        'file'    => 'Das: Attribut muss sein: Größe Kilobyte.',
        'string'  => 'Das: Attribut muss sein: Größe Zeichen.',
        'array'   => 'Das: Attribut muss enthalten: Größe Elemente.',
    ],
    'string'               => 'Das: -Attribut muss eine Zeichenkette sein.',
    'timezone'             => 'Das: Attribut muss eine gültige Zone sein.',
    'unique'               => 'Das: Attribut wurde bereits vergeben.',
    'uploaded'             => 'Das: Attribut konnte nicht hochgeladen werden.',
    'url'                  => 'Das: Attributformat ist ungültig.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
