<?php

return [

    //general
    'error' => 'Dogodila se greška! Molimo pokušajte ponovno',
    'validation_error' => 'Forma je nepravilno popunjena',
    'login_error' => 'Podaci za prijavu nisu točni',

    'permission_denied' => 'Nemate ovlasti za izvršavanje pokrenute akcije',

    //resources management
    'general_code_uniqueness' => ' sa upisanom šifrom već postoji',
    'general_employee_code_uniqueness' => ' sa upisanim matičnim brojem već postoji',
    'general_oib_uniqueness' => ' sa upisanom OIB-om već postoji',
    'general_required' => ' je obavezno polje',
    'email_format' => 'Email adresa nije dobro formatirana',
    'general_email_uniqueness' => ' sa upisanom email adresom već postoji',
    'password_confirmation' => 'Lozinka mora biti potvrđena',

    //manipulations
    'invalid_resource_type' => 'Odabrani tip resursa ne postoji',
    'invalid_resource' => '[1,2] Odabrani :resource ne postoji|{3} Odabrana :resource ne postoji|{4} Odabrano :resource ne postoji|'.
        '{5} Odabrani :resource ne postoji',
    'invalid_site' => 'Odabrano gradilište ne postoji',
    'site_resource_already_exists' => ':Resource se već nalazi na odabranom gradilištu',
    'invalid_parking' => 'Odabrano parkiralište ne postoji',
    'parking_resource_already_exists' => ':Resource se već nalazi na odabranom parkiralištu',
    'invalid_status' => 'Odabrani status ne postoji',
    'status_resource_already_exists' => 'Zaposlenik se već nalazi na :status',
    'employee_not_on_site' => 'Zaposlenik se ne nalazi na odabranom gradilištu',

    //daily work activities
    'dwa_access_denied' => 'Nemate pravo korisniti dnevni radni list stroja',
    'dwa_current_user_site_not_assigned' => 'Trenutno niste dodijeljeni ni na jedno gradilište',
    'dwa_current_site_employees_not_assigned' => 'Ovom gradilištu nije dodijeljen ni jedan strojar',
    'dwa_current_site_machines_not_assigned' => 'Ovom gradilištu nije dodijeljen ni jedan stroj',
    'dwa_resource_is_not_on_employee_site' => ':Resource se ne nalazi na vašem gradilištu',
    'activity_end_time' => 'Vrijeme završetka mora biti veće od početnog vremena',
    'activity_available_time' => 'Već postoji aktivnost za odabrani vremenski period',
    'end_working_hours' => 'Završni radni sat mora biti veći od početnog',
    'dwa_confirmation_not_allowed' => 'Nemate pravo potvrde ovog radnog lista stroja',
    'dwa_edit_not_allowed' => 'Nemate pravo editirati ovoj radni list stroja'
];
