<?php

use App\Http\Controllers\Admin\AccountSettingsController;
use App\Http\Controllers\Admin\AlumniPdfController;
use App\Http\Controllers\Admin\CareerController as AdminCareerController;
use App\Http\Controllers\Admin\FormReportsController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ReportGenerationWithGraphs;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\TracerReportsController;
use App\Http\Controllers\Admin\UserManagerController;
use App\Http\Controllers\Admin\UserReportsController;
use App\Http\Controllers\Admin\UserReportToPdfController;
use App\Http\Controllers\Auth\LoginMailController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\TemporaryPassoword;
use App\Http\Controllers\Modules\CareerController;
use App\Http\Controllers\Modules\EifToPdfController;
use App\Http\Controllers\Modules\FormsController;
use App\Http\Controllers\Modules\HomeController as ModulesHomeController;
use App\Http\Controllers\Modules\PdsToPdfController;
use App\Http\Controllers\Modules\ProfileController;
use App\Http\Controllers\Modules\SasToPdfController;
use App\Http\Controllers\Modules\TracerController;
use App\Http\Controllers\SuperAdmin\AdminManagementController;
use App\Http\Controllers\SuperAdmin\AnnouncementController;
use App\Http\Controllers\SuperAdmin\HomeController as SuperAdminHomeController;
use App\Http\Controllers\SuperAdmin\NewsController;
use App\Http\Controllers\Admin\TracerAnswers;
use App\Http\Controllers\Admin\TracerStudiesGenerator;
use App\Http\Controllers\Modules\EifController;
use App\Http\Controllers\SuperAdmin\TracerManagementController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

const CONTROLLER_ADMIN_USER_MANAGER = 'App\Http\Controllers\Admin\UserManagerController';
const CONTROLLER_ADMIN_CAREER = 'App\Http\Controllers\Admin\CareerController';

const CONTROLLER_ADMIN_REPORTS = "App\Http\Controllers\Admin\ReportsController";
const CONTROLLER_ADMIN_USER_REPORTS = "App\Http\Controllers\Admin\UserReportsController";
const CONTROLLER_ADMIN_FORM_REPORTS = "App\Http\Controllers\Admin\FormReportsController";

const CONTROLLER_ADMIN_ACCOUNT_SETTINGS = "App\Http\Controllers\Admin\AccountSettingsController";

const CONTROLLER_SUPERADMIN_ANNOUNCEMENT_SETTINGS = "App\Http\Controllers\SuperAdmin\AnnouncementController";
const CONTROLLER_SUPERADMIN_NEWS_SETTINGS = "App\Http\Controllers\SuperAdmin\NewsController";
const CONTROLLER_SUPERADMIN_ADMIN_MANAGEMENT = "App\Http\Controllers\SuperAdmin\AdminManagementController";

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
|--------------------------------------------------------------------------
| Homepage
|--------------------------------------------------------------------------
| Homepage - Landing page Routing
*/
Route::group(
    [
        'controller' => ModulesHomeController::class
    ],
    function () {
        Route::get('/', 'getLandingPage')
            ->middleware('isLoggedIn')
            ->name('landingPage');

        Route::get('/home', 'getUserHomepage')
            ->middleware(['auth', 'isAdmin', 'checkAccountStatus', 'notFinishedSetup'])
            ->name('user.homepage');

        Route::get('/contacts', 'getContactsPage')
            ->middleware(['auth', 'isAdmin', 'checkAccountStatus', 'notFinishedSetup'])
            ->name('user.getContactsPage');

        Route::get('/about-us', 'getAboutPage')
            ->middleware(['auth', 'isAdmin', 'checkAccountStatus', 'notFinishedSetup'])
            ->name('user.getAboutPage');
    }
);

/*
|--------------------------------------------------------------------------
| Temporary Password - Email
|--------------------------------------------------------------------------
|
| Routing for sending temporary password through email
|
*/
Route::group(
    [
        'controller' => LoginMailController::class,
        'prefix'     => 'mail',
        'as'         => 'mail.',
    ],
    function () {
        Route::get('sendTemporaryPassword/{email}/{stud_number}', 'sendTemporaryPassword')
            ->name('sendTemporaryPassword');
    }
);

/*
|--------------------------------------------------------------------------
| Temporary Password
|--------------------------------------------------------------------------
|
| Routing for sending temporary password through email
|
*/
Route::group(
    [
        'controller' => TemporaryPassoword::class,
        'prefix'     => 'login',
        'as'         => 'login.',
    ],
    function () {
        Route::get('temporary-password', 'getTemporaryPassword')
            ->name('getTemporaryPassword');
    }
);

/*
|--------------------------------------------------------------------------
| Reset Password
|--------------------------------------------------------------------------
|
| Routing for reseting password
|
*/
Route::group(
    [
        'controller' => ResetPasswordController::class,
        'prefix'     => 'login',
        'as'         => 'login.',
    ],
    function () {
        Route::get('reset-password', 'getResetPassword')
            ->name('getResetPassword');

        Route::post('reset-password/send', 'sendResetPassword')
            ->name('sendResetPassword');

        Route::get('change-password/{email}', 'changePassword')
            ->name('changePassword');

        Route::post('changing-password', 'changingPassword')
            ->name('changingPassword');
    }
);

/*
|--------------------------------------------------------------------------
| Login - Registration
|--------------------------------------------------------------------------
|
| Routing for Login and Registration
|
*/
require __DIR__ . '/auth.php';


/*
|--------------------------------------------------------------------------
| Career Module
|--------------------------------------------------------------------------
|
| Routing for career functions and modules of user / alumni side
|
*/
Route::group(
    [
        'controller' => CareerController::class,
        'prefix'     => 'careers',
        'as'         => 'userCareer.',
        'middleware' => ['isAdmin', 'auth', 'notFinishedSetup']
    ],
    function () {
        Route::get('/', 'getCareerIndex')
            ->name('index');

        Route::post('create-text-career', 'addTextCareer')
            ->name('addTextCareer');

        Route::post('create-image-career', 'addImageCareer')
            ->name('addImageCareer');
    }
);

/*
|--------------------------------------------------------------------------
| Tracer Module
|--------------------------------------------------------------------------
|
| Routing for Answering and Updating the Tracer form in user / alumni side
|
*/
Route::group(
    [
        'controller' => TracerController::class,
        'prefix'     => 'tracer',
        'as'         => 'userTracer.',
        'middleware' => ['isAdmin', 'auth']
    ],
    function () {
        Route::get('/', 'getTracerIndex')
            ->name('getTracerIndex')->middleware('notFinishedSetup');

        Route::get('update-tracer', 'getUpdatePage')
            ->name('getUpdatePage')->middleware('notFinishedSetup');

        Route::get('answer-tracer', 'getAnswerUnemployedPage')
            ->name('getAnswerUnemployedPage')->middleware('notFinishedSetup');

        Route::get('answer', 'getAnswerPage')
            ->name('getAnswerPage')->middleware('isFinishedSetup');
        // Route::get('save', 'saveAnswers')
        //     ->name('saveAnswers');
        Route::post('saveUpdate', 'saveUpdateTracer')
        ->name('saveUpdate');
    }
);

Route::post('save', [TracerController::class,'saveAnswers'])->name('userTracer.saveAnswers');

/*
|--------------------------------------------------------------------------
| User Post Registration
|--------------------------------------------------------------------------
|
| Routing for the post registration requirements - Tracer and
| Profile Setup
|
*/
Route::group(
    [
        'controller' => ProfileController::class,
        'as'         => 'userProfile.',
        'prefix'     => 'profile',
        'middleware' => ['isAdmin', 'auth', 'isFinishedSetup']
    ],
    function () {
        Route::get('setting-up-account', 'getPostReg')
            ->name('set-up');
    }
);

/*
|--------------------------------------------------------------------------
| User Profile Module
|--------------------------------------------------------------------------
|
| Routing for setting up user profile and updating user profile
|
*/
Route::group(
    [
        'controller' => ProfileController::class,
        'prefix'     => 'profile',
        'as'         => 'userProfile.',
        'middleware' => ['isAdmin', 'auth']
    ],
    function () {
        Route::get('/', 'getProfileIndex')
            ->name('index')->middleware('notFinishedSetup');

        Route::get('profile-setup', 'getProfileSetup')
            ->name('getProfileSetup')->middleware('isFinishedSetup');

        Route::post('setup-profile', 'updateProfile')
            ->name('updateProfile')->middleware('isFinishedSetup');

        Route::post('update-profile', 'updateUserAccount')
            ->name('updateUserAccount')->middleware('notFinishedSetup');
    }
);

/*
|--------------------------------------------------------------------------
| Forms Module
|--------------------------------------------------------------------------
|
| Routing for getting the forms module page and for getting the other forms
|
*/
Route::group(
    [
        'controller' => FormsController::class,
        'prefix'     => 'forms',
        'as'         => 'userForm.',
        'middleware' => ['isAdmin', 'auth', 'notFinishedSetup']
    ],
    function () {
        Route::get('/', 'getFormIndex')
            ->name('index');

        Route::get('personal-data-sheet', 'getPDS')
            ->name('getPDS');

        Route::get('exit-interview', 'getExiteInterview')
            ->name('getExiteInterview');

        Route::get('sas-form', 'getSAS')
            ->name('getSAS');
    }
);

/*
|--------------------------------------------------------------------------
| PDS Form Download
|--------------------------------------------------------------------------
|
| Routing for downloading the PDS Form as PDF
|
*/
Route::group(
    [
        'controller' => PdsToPdfController::class,
        'prefix'     => 'download',
        'as'         => 'userForm.',
        'middleware' => ['auth']
    ],
    function () {
        Route::post('form-pds', 'PDS_to_PDF')
            ->name('PDS_to_PDF');
    }
);

/*
|--------------------------------------------------------------------------
| EIF Form Download
|--------------------------------------------------------------------------
|
| Routing for downloading the EIF Form as PDF
|
*/
Route::group(
    [
        'controller' => EifToPdfController::class,
        'prefix'     => 'download',
        'as'         => 'userForm.',
        'middleware' => ['auth']
    ],
    function () {
        Route::post('form-exit-interview', 'EIF_TO_PDF')
            ->name('EIF_TO_PDF');
    }
);

/*
|--------------------------------------------------------------------------
| SAS Form Download
|--------------------------------------------------------------------------
|
| Routing for downloading the SAS Form as PDF
|
*/
Route::group(
    [
        'controller' => SasToPdfController::class,
        'prefix'     => 'download',
        'as'         => 'userForm.',
        'middleware' => ['auth']
    ],
    function () {
        Route::post('form-sas', 'SAS_TO_PDF')
            ->name('SAS_TO_PDF');
    }
);

/*
|--------------------------------------------------------------------------
| Homepage
|--------------------------------------------------------------------------
|
| Routing for Homepage in Admin Side
|
*/
Route::group(
    [
        'controller' => HomeController::class
    ],
    function () {

        Route::get('/admin', 'getAdminHomepage')
            ->middleware(['auth', 'isLoggedIn', 'isUser'])
            ->name('admin.homepage');
        Route::get('/getTracerReminder', 'getTracerReminder')
            ->middleware(['auth', 'isLoggedIn', 'isUser'])
            ->name('admin.tracerreminder');
        Route::get('/getTracerHistory', 'getTracerHistory')
            ->middleware(['auth', 'isLoggedIn', 'isUser'])
            ->name('admin.view-tracer-history');
        Route::get('/WeekUpdates', 'updatesInAWeek')
            ->middleware(['auth', 'isLoggedIn', 'isUser'])
            ->name('admin.updatesInAWeek');
        Route::get('/MonthUpdates', 'updatesInAMonth')
            ->middleware(['auth', 'isLoggedIn', 'isUser'])
            ->name('admin.updatesInAMonth');
        Route::get('/6MonthUpdates', 'updatesIn6Months')
            ->middleware(['auth', 'isLoggedIn', 'isUser'])
            ->name('admin.updatesIn6Months');
    }
);

Route::group(
    [
        'controller' => TracerAnswers::class
    ],
    function () {
        Route::get('/getTracerAnswers', 'getTracerAnswers')
            ->middleware(['auth', 'isLoggedIn', 'isUser'])
            ->name('admin.view-tracer-answers');
        Route::get('/clearFilter', 'clearFilter')
            ->middleware(['auth', 'isLoggedIn', 'isUser'])
            ->name('TracerAnswers.clearFilter');
        Route::get('seacrhAlumniTracer', 'seacrhAlumniTracer')
            ->middleware(['auth', 'isLoggedIn', 'isUser'])
            ->name('TracerAnswers.searchAlumniTracer');
        Route::get('sortAlumniTracer', 'sortAlumniTracer')
            ->middleware(['auth', 'isLoggedIn', 'isUser'])
            ->name('TracerAnswers.sortAlumniTracer');
        Route::get('tracer/{alumni_id}','show_tracer')
            ->middleware(['auth', 'isLoggedIn', 'isUser'])
            ->name('user.show');
    }
);
// Route::get('users/{alumni_id}', [TracerAnswers::class,'show_tracer'])->name('user.show');

Route::group(
    [
        'controller' => TracerStudiesGenerator::class,
        'middleware' => ['auth', 'isLoggedIn', 'isUser']
    ],
    function () {
        Route::get('/export_TracerStudies', 'export_TracerStudies')
            ->name('export_TracerStudies');

        Route::post('/postDataFromForms', 'postDataFromForm')
            ->name('tracerstudies.postDataFromForm');

        Route::post('/tracerStudiesPDF', 'getDataFromForm')
            ->name('tracerstudies.getDataFromForm');

        Route::get('/view_TracerStudies', 'view_TracerStudies')
            ->name('view_TracerStudies');
    }
);

// Route::get('/export_TracerStudies', [TracerStudiesGenerator::class, 'export_TracerStudies'])->name('export_TracerStudies');
// Route::post('/postDataFromForms', [TracerStudiesGenerator::class, 'postDataFromForm'])->name('tracerstudies.postDataFromForm');
// Route::get('/getDataFromForms/{batch_from}/{batch_to}', [TracerStudiesGenerator::class, 'getDataFromForm'])->name('tracerstudies.getDataFromForm');
// Route::get('/view_TracerStudies', [TracerStudiesGenerator::class, 'view_TracerStudies'])->name('view_TracerStudies');


/*
|--------------------------------------------------------------------------
| User Management Module
|--------------------------------------------------------------------------
|
| Routing for uploading list of graduates, update information of alumni,
| checking the status of thier forms and viewing the answer of thier forms
|
*/
Route::group(
    [
        'controller' => UserManagerController::class,
        'prefix'     => 'admin/user-management',
        'as'         => 'adminUserManagement.',
        'middleware' => ['isUser', 'auth']
    ],
    function () {
        Route::get('/', 'getAlumniManager')
            ->name('getAlumniManager');

        Route::post('add-alumni-list', 'addAlumniList')
            ->name('addAlumniList');

        Route::post('update-alumni-profile', 'updateAlumniInfo')
            ->name('updateAlumniInfo');

        Route::post('delete-alumni-profile', 'deleteAlumniProfile')
            ->name('deleteAlumniProfile');

        Route::get('download-template', 'downloadListTemplate')
            ->name('downloadListTemplate');

        Route::post('reset-pds', 'resetPds')
            ->name('resetPds');

        Route::post('reset-eif', 'resetEif')
            ->name('resetEif');

        Route::post('reset-sas', 'resetSas')
            ->name('resetSas');
    }
);

/*
|--------------------------------------------------------------------------
| Alumni Answered Forms
|--------------------------------------------------------------------------
|
| Routing for viewing the alumni answered forms for checking
|
*/
Route::group(
    [
        'controller' => AlumniPdfController::class,
        'prefix'     => 'admin/alumni-form',
        'as'         => 'adminGetPdf.',
        'middleware' => ['isUser', 'auth']
    ],
    function () {
        Route::post('view-pds', 'downloadPDS')
            ->name('downloadPDS');

        Route::post('view-eif', 'downloadEI')
            ->name('downloadEI');

        Route::post('view-sas', 'downloadSAS')
            ->name('downloadSAS');
    }
);

/*
|--------------------------------------------------------------------------
| Career Module
|--------------------------------------------------------------------------
|
| Routing for approving career posting, creating a career posting and
| deleting career posting
|
*/
Route::group(
    [
        'controller' => AdminCareerController::class,
        'prefix'     => 'admin/careers',
        'as'         => 'adminCareer.',
        'middleware' => ['isUser', 'auth']
    ],
    function () {
        Route::get('/', 'getAdminCareerIndex')
            ->name('getAdminCareerIndex');

        Route::get('requests', 'getCareerRequest')
            ->name('getCareerRequest');

        Route::patch('approve-career/{career_id}', 'approveCareer')
            ->name('approveCareer');

        Route::post('reject-career', 'rejectCareer')
            ->name('rejectCareer');

        Route::post('add-text-career', 'addTextCareer')
            ->name('addTextCareer');

        Route::post('add-image-career', 'addImageCareer')
            ->name('addImageCareer');
    }
);

/*
|--------------------------------------------------------------------------
| Form and Tracer Reports Module
|--------------------------------------------------------------------------
|
| Routing for getting the form and tracer reports module page
|
*/
Route::group(
    [
        'controller' => ReportsController::class,
        'prefix'     => 'admin',
        'as'         => 'adminReports.',
        'middleware' => ['isUser', 'auth']
    ],
    function () {
        Route::get('form-reports', 'getFormReports')
            ->name('getFormReports');

        Route::get('form-reports-charts', 'getFormReportsCharts')
            ->name('getFormReportsCharts');

        Route::get('tracer-reports', 'getTracerReports')
            ->name('getTracerReports');
    }
);

/*
|--------------------------------------------------------------------------
| Alumni Reports Module
|--------------------------------------------------------------------------
|
| Routing for getting the alumni reports page
|
*/
Route::group(
    [
        'controller' => UserReportsController::class,
        'as'         => 'adminReports.',
        'middleware' => ['isUser', 'auth']
    ],
    function () {
        Route::get('alumni-reports', 'getUserReports')
            ->name('getUserReports');
    }
);

/*
|--------------------------------------------------------------------------
| Alumni Reports Generation
|--------------------------------------------------------------------------
|
| Routing for generating a pdf file for the alumni reports
|
*/
Route::group(
    [
        'controller' => UserReportToPdfController::class,
        'prefix'     => 'admin/alumni-reports',
        'as'         => 'adminReports.',
        'middleware' => ['isUser', 'auth']
    ],
    function () {
        Route::post('download', 'getUserReportPdf')
            ->name('getUserReportPdf');
    }
);

/*
|--------------------------------------------------------------------------
| Tracer Reports Generation
|--------------------------------------------------------------------------
|
| Routing for generating a pdf file for tracer reports
|
*/
Route::group(
    [
        'controller' => TracerReportsController::class,
        'prefix'     => 'admin/tracer-reports',
        'as'         => 'adminReports.',
        'middleware' => ['isUser', 'auth']
    ],
    function () {
        Route::post('download', 'tracerReportToPdf')
            ->name('tracerReportToPdf');
    }
);

/*
|--------------------------------------------------------------------------
| New Form Reports Generation
|--------------------------------------------------------------------------
| Updated Form reports generation
| Routing for generating a pdf file for form reports
|
*/
Route::group(
    [
        'controller' => EifController::class,
        'prefix'     => 'admin/eif-reports',
        'as'         => 'eifReports.',
        'middleware' => ['isUser', 'auth']
    ],
    function () {
        Route::post('eifpdf', 'EIFtoPDF')
            ->name('generateEIFReport');
    }
);

/*
|--------------------------------------------------------------------------
| Form Reports Generation
|--------------------------------------------------------------------------
|
| Routing for generating a pdf file for form reports
|
*/
Route::group(
    [
        'controller' => FormReportsController::class,
        'prefix'     => 'admin/form-reports',
        'as'         => 'adminReports.',
        'middleware' => ['isUser', 'auth']
    ],
    function () {
        Route::post('download', 'generateFormReport')
            ->name('generateFormReport');
    }
);

/*
|--------------------------------------------------------------------------
| Account Setting Module
|--------------------------------------------------------------------------
|
| Routing for updating account credentials in admin side
|
*/
Route::group(
    [
        'controller' => AccountSettingsController::class,
        'prefix'     => 'admin/account-settings',
        'as'         => 'adminSettings.',
        'middleware' => ['isUser', 'auth']
    ],
    function () {
        Route::get('/', 'getAccountSettings')
            ->name('getAccountSettings');

        Route::post('update', 'updateAccount')
            ->name('updateAccount');
    }
);

/*
|--------------------------------------------------------------------------
| Super Admin Homepage
|--------------------------------------------------------------------------
|
| Routing for getting the super admin homepage
|
*/
Route::group(
    [
        'controller' => SuperAdminHomeController::class
    ],
    function () {

        Route::get('/super-admin', 'getSuperAdminIndex')
            ->middleware(['auth', 'isLoggedIn', 'isUser'])
            ->name('superAdmin.getSuperAdminIndex');
    }
);

/*
|--------------------------------------------------------------------------
| Announcement Module
|--------------------------------------------------------------------------
|
| Routing for creating a post regarding announcements related to PUP
|
*/
Route::group(
    [
        'controller' => AnnouncementController::class,
        'prefix'     => 'super-admin/announcement',
        'as'         => 'superAdmin.',
        'middleware' => ['isUser', 'auth']
    ],
    function () {

        Route::get('/', 'getAnnouncementSettings')
            ->name('getAnnouncementSettings');

        Route::post('add-announcement', 'postAnnouncement')
            ->name('postAnnouncement');

        Route::post('delete-announcement', 'deleteAnnouncement')
            ->name('deleteAnnouncement');
    }
);

/*
|--------------------------------------------------------------------------
| News Module
|--------------------------------------------------------------------------
|
| Routing for creating a post regarding news related to PUP
|
*/
Route::group(
    [
        'controller' => NewsController::class,
        'prefix'     => 'super-admin/news',
        'as'         => 'superAdmin.',
        'middleware' => ['isUser', 'auth']
    ],
    function () {

        Route::get('/', 'getNewsSettings')
            ->name('getNewsSettings');

        Route::post('add-news', 'postNews')
            ->name('postNews');

        Route::post('delete-news', 'deleteNews')
            ->name('deleteNews');
    }
);

/*
|--------------------------------------------------------------------------
| Tracer Management Module
|--------------------------------------------------------------------------
|
| Routing for managing and editing tracer forms
|
*/
Route::group(
    [
        'controller' => TracerManagementController::class,
        'prefix'     => 'super-admin/tracer',
        'as'         => 'superAdmin.',
        'middleware' => ['isUser', 'auth']
    ],
    function () {

        Route::get('/', 'getTracerManagement')
            ->name('getTracerManagement');
        Route::get('getAddQuestion', 'getAddQuestion')
            ->name('getAddQuestion'); 
        Route::get('saveQuestion', 'saveQuestion')
            ->name('saveQuestion');
        Route::get('deleteQuestion', 'deleteQuestion')
            ->name('deleteQuestion');
        Route::get('getEditQuestion/{question_id}', 'getEditQuestion')
            ->name('getEditQuestion');
        Route::get('saveEditQuestion', 'saveEditQuestion')
            ->name('saveEditQuestion');
        Route::get('deleteOption', 'deleteOption')
            ->name('deleteOption');
        Route::get('editOption', 'editOption')
            ->name('editOption');
        Route::get('addOption', 'addOption')
            ->name('addOption');
        Route::get('getTracerManagementVersion', 'getTracerManagementVersion')
            ->name('getVersion');

    }
);


/*
|--------------------------------------------------------------------------
| Admin Management Module
|--------------------------------------------------------------------------
|
| Routing for adding and removing of admins
|
*/
Route::group(
    [
        'controller' => AdminManagementController::class,
        'prefix'     => 'super-admin/admin-management',
        'as'         => 'superAdmin.',
        'middleware' => ['isUser', 'auth']
    ],
    function () {
        Route::get('/', 'getAdminManager')
            ->name('getAdminManager');

        Route::get('new-admin', 'getAddNewAdmin')
            ->name('getAddNewAdmin');

        Route::post('save-admin', 'saveNewAdmin')
            ->name('saveNewAdmin');
        Route::post('delete-admin', 'deleteAdmin')
            ->name('deleteAdmin');
    }
);

/*
|--------------------------------------------------------------------------
| New Report Generators with graphs
|--------------------------------------------------------------------------
|
| Routing for Report Generators with graphs
|
*/
Route::group(
    [
        'controller' => ReportGenerationWithGraphs::class,
        'prefix'     => 'reports/',
        'as'         => 'admin.',
        'middleware' => ['isUser', 'auth']
    ],
    function () {
        Route::post('/sas', 'getReportsPage')
            ->name('sasReports');
        Route::post('/eif', 'getEifReportsPage')
            ->name('eifReports');
        Route::get('/pdsform', 'getPDSQuestions')
            ->name('getPDSQuestions');
        Route::post('/printReports', 'printSASReports')
            ->name('printSASReports');
        Route::get('/printTemplate', 'getTemplate')
            ->name('getTemplate');  
    }
);

