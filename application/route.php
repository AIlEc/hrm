<?php

use think\Route;

Route::group('api/:version', function () {
    //Employment
    Route::group('/employment', function () {
        Route::get('/:id','api/:version.Employment/getOneByID', [], ['id' => '\d+']);
        Route::get('/paginate', 'api/:version.Employment/getEmploymentPaginate');
        Route::post('/add', 'api/:version.Employment/createEmployment');
        Route::post('/edit', 'api/:version.Employment/updateEmployment');
        Route::post('/change_status', 'api/:version.Employment/changeEmploymentStatus');
        Route::post('/del', 'api/:version.Employment/deleteEmployment');
        Route::post('/batch_del', 'api/:version.Employment/delBatchEmployments');
    });

    //User
    Route::group('/user',function(){
        Route::get('/agent_paginate', 'api/:version.User/getAgentPaginate');
        Route::get('/auditor_paginate', 'api/:version.User/getAuditorPaginate');
        Route::post('/register', 'api/:version.User/agentRegister');
        Route::post('/complete', 'api/:version.User/agentComplete');
        Route::post('/auditor_register', 'api/:version.User/createAuditor');
        Route::post('/del', 'api/:version.User/deleteUser');
        Route::post('/batch_del', 'api/:version.User/delBatchUsers');
        Route::get('/detail', 'api/:version.User/getUserDetail');
        Route::get('/backerage', 'api/:version.User/getUserMoney');
        Route::get('/withdraw', 'api/:version.User/withdraw');
        Route::get('/withdraw_list', 'api/:version.User/withdrawListOfUser');
        Route::post('/check_verify', 'api/:version.User/checkVerify');
        Route::post('/change_psw', 'api/:version.User/changePassword');
    });

    //Admin
    Route::group('/admin',function(){
        Route::get('', 'api/:version.ThirdApp/getAllThird');
        Route::get('/:id', 'api/:version.ThirdApp/getOneByID', [], ['id' => '\d+']);
        Route::post('/add', 'api/:version.ThirdApp/createThird');
        Route::post('/del', 'api/:version.ThirdApp/deleteThird');
        Route::post('/edit', 'api/:version.ThirdApp/updateThird');
    });

    //Image
    Route::group('/image',function(){
        Route::post('/upload_agent', 'api/:version.Image/uploadAgentImages');
        Route::post('/upload_staff', 'api/:version.Image/uploadStaffImages');
    });

    //Staff
    Route::group('/staff',function(){
        Route::post('/agent/add', 'api/:version.Staff/createStaff');
        Route::get('/agent', 'api/:version.Staff/getStaffOfAgent');
        Route::get('/auditor', 'api/:version.Staff/getStaffOfAuditor');
        Route::get('/auditor/on_job', 'api/:version.Staff/getStaffOfAuditorOnJob');
        Route::get('/auditor/layoff', 'api/:version.Staff/getStaffOfAuditorsLayOff');
        Route::get('/agent/inter', 'api/:version.Staff/getInterStaff');
        Route::get('/agent/hire', 'api/:version.Staff/getHireStaff');
        Route::get('/agent/layoff', 'api/:version.Staff/getLayoffStaff');
        Route::get('/agent/no_job', 'api/:version.Staff/getNoJobStaff');
        Route::get('/agent/on_apply', 'api/:version.Staff/getOnApplyStaff');
        Route::get('/:id', 'api/:version.Staff/getStaffByID');
        Route::post('/agent/apply', 'api/:version.Staff/applyStaffToEmployment');
        Route::get('/employment/:id', 'api/:version.Staff/getStaffEmployments');
        Route::post('/interview', 'api/:version.Staff/checkInterview');
        Route::post('/on_job', 'api/:version.Staff/onJob');
        Route::post('/leave_job', 'api/:version.Staff/leaveJob');
        Route::post('/auditor/add', 'api/:version.Staff/addStaffByAuditorDirectly');
        Route::post('/auditor/del_staff', 'api/:version.Staff/delStaffByAuditor');
    });

    //takework
    Route::get('/take_work', 'api/:version.Takework/getTakeWorks');

    //commission
    Route::group('/commission',function(){
        Route::get('', 'api/:version.Commission/getCommissionByID');
        Route::get('/history', 'api/:version.Commission/getCommissionHistory');
        Route::get('/settle/:id', 'api/:version.Commission/settleCommission');
        Route::get('/money', 'api/:version.Commission/getAllValidCommission');
        Route::get('/houses', 'api/:version.Commission/commissionAllValid');
    });

    //token
    Route::post('/get_app', 'api/:version.Token/getAppToken');
    Route::post('/login', 'api/:version.Token/getUserToken');

    //message
    Route::post('/verify', 'api/:version.Message/sendVerifyCode');
    Route::post('/psw_verify', 'api/:version.Message/sendVerifyCodeForPSW');

});

