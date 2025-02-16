<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Codebase Name
    |--------------------------------------------------------------------------
    |
    | Name of Codebase
    |
    */

    'codebase' => 'UNIT3D Community Edition (Nex-Gen Torrent Tracker) v2.2.7',

    /*
    |--------------------------------------------------------------------------
    | Site title
    |--------------------------------------------------------------------------
    |
    | Title of Site
    |
    */

    'title' => 'UNIT3D',

    /*
    |--------------------------------------------------------------------------
    | Site SubTitle
    |--------------------------------------------------------------------------
    |
    | SubTitle
    |
    */

    'subTitle' => 'Built On Laravel',

    /*
    |--------------------------------------------------------------------------
    | Site email
    |--------------------------------------------------------------------------
    |
    | Email address to send emails
    |
    */

    'email' => env('DEFAULT_OWNER_EMAIL', 'unit3d@none.com'),

    /*
    |--------------------------------------------------------------------------
    | Meta description
    |--------------------------------------------------------------------------
    |
    | Default meta description content
    |
    */

    'meta_description' => 'Built On Laravel',

    /*
    |--------------------------------------------------------------------------
    | Site Birthdate
    |--------------------------------------------------------------------------
    |
    | Date Site Was Born
    |
    */
    'birthdate' => 'December 30th 2017',

    /*
    |--------------------------------------------------------------------------
    | Freelech State
    |--------------------------------------------------------------------------
    |
    | Global Freeleech
    |
    */
    'freeleech' => false,

    'freeleech_until' => '12/23/2017 3:00 PM EST',

    /*
    |--------------------------------------------------------------------------
    | Double Upload State
    |--------------------------------------------------------------------------
    |
    | Global Double Upload
    |
    */
    'doubleup' => false,

    /*
    |--------------------------------------------------------------------------
    | Min Ratio
    |--------------------------------------------------------------------------
    |
    | Minimum Ratio To Download
    |
    */

    'ratio' => 0.4,

    /*
    |--------------------------------------------------------------------------
    | Invite only
    |--------------------------------------------------------------------------
    |
    | Invite System On/Off (Open Reg / Closed)
    | Expire time in days
    |
    | Restricted mode for invites. If set to true, invites will be restricted
    | Exempt these groups from the invite restrictions
    */
    'invite-only'   => true,
    'invite_expire' => '14',

    'invites_restriced' => false,
    'invite_groups'     => [
        'Administrator',
        'Owner',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Users Stats
    |--------------------------------------------------------------------------
    |
    | This will be the upload and download given to new members. (In Bytes!)
    | Default: 50GB Upload and 1GB Download
    */
    'default_upload'   => '53687091200',
    'default_download' => '1073741824',

    /*
    |--------------------------------------------------------------------------
    | Default Site Style
    |--------------------------------------------------------------------------
    | 0 = Light Theme
    | 1 = Galactic Theme
    | 2 = Dark Blue Theme
    | 3 = Dark Green Theme
    | 4 = Dark Pink Theme
    | 5 = Dark Purple Theme
    | 6 = Dark Red Theme
    | 7 = Dark Teal Theme
    | 8 = Dark Yellow Theme
    */
    'default_style' => 0,

    /*
    |--------------------------------------------------------------------------
    | Default Font Awesome Style
    |--------------------------------------------------------------------------
    | fas = Solid
    | far = Regular
    | fal = Light
    */
    'font-awesome' => 'fas',

    /*
    |--------------------------------------------------------------------------
    | Application Signups
    |--------------------------------------------------------------------------
    | True/1 = Enabled
    | False/0 = Disabled
    */
    'application_signups' => false,

    /*
    |--------------------------------------------------------------------------
    | Rules Page URL
    |--------------------------------------------------------------------------
    | Example: 1
    */
    'rules_url' => env('APP_URL').'/pages/1',

    /*
    |--------------------------------------------------------------------------
    | FAQ Page URL
    |--------------------------------------------------------------------------
    | Example: 2
    */
    'faq_url' => env('APP_URL').'/pages/2',

    /*
    |--------------------------------------------------------------------------
    | Upload Guide Page URL For Upload Page
    |--------------------------------------------------------------------------
    | Example: 4
    */
    'upload-guide_url' => env('APP_URL').'/pages/4',
];
