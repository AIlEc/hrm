<?php

use think\Route;

//Employment
Route::get('api/:version/employment/:id','api/:version.Employment/getOneByID',[],['id'=>'\d+']);
Route::get('api/:version/employment/paginate','api/:version.Employment/getEmploymentPaginate');
Route::post('api/:version/employment/add','api/:version.Employment/createEmployment');
Route::post('api/:version/employment/edit','api/:version.Employment/updateEmployment');
Route::post('api/:version/employment/change_status','api/:version.Employment/changeEmploymentStatus');
Route::post('api/:version/employment/del','api/:version.Employment/deleteEmployment');
Route::post('api/:version/employment/batch_del','api/:version.Employment/delBatchEmployments');

//User
Route::get('api/:version/user/agent_paginate','api/:version.User/getAgentPaginate');
Route::get('api/:version/user/auditor_paginate','api/:version.User/getAuditorPaginate');
Route::post('api/:version/user/register','api/:version.User/agentRegister');
Route::post('api/:version/user/complete','api/:version.User/agentComplete');
Route::post('api/:version/user/auditor_register','api/:version.User/createAuditor');
Route::post('api/:version/user/del','api/:version.User/deleteUser');
Route::post('api/:version/user/batch_del','api/:version.User/delBatchUsers');
Route::get('api/:version/user/detail','api/:version.User/getUserDetail');
Route::get('api/:version/user/backerage','api/:version.User/getUserMoney');
Route::get('api/:version/user/withdraw','api/:version.User/withdraw');
Route::get('api/:version/user/withdraw_list','api/:version.User/withdrawListOfUser');
Route::post('api/:version/user/check_verify','api/:version.User/checkVerify');
Route::post('api/:version/user/change_psw','api/:version.User/changePassword');

//Admin
Route::get('api/:version/admin','api/:version.ThirdApp/getAllThird');
Route::get('api/:version/admin/:id','api/:version.ThirdApp/getOneByID',[],['id'=>'\d+']);
Route::post('api/:version/admin/add','api/:version.ThirdApp/createThird');
Route::post('api/:version/admin/del','api/:version.ThirdApp/deleteThird');
Route::post('api/:version/admin/edit','api/:version.ThirdApp/updateThird');

//Image
Route::post('api/:version/image/upload_agent','api/:version.Image/uploadAgentImages');
Route::post('api/:version/image/upload_staff','api/:version.Image/uploadStaffImages');

//Staff
Route::post('api/:version/staff/agent/add','api/:version.Staff/createStaff');
Route::get('api/:version/staff/agent','api/:version.Staff/getStaffOfAgent');
Route::get('api/:version/staff/auditor','api/:version.Staff/getStaffOfAuditor');
Route::get('api/:version/staff/auditor/on_job','api/:version.Staff/getStaffOfAuditorOnJob');
Route::get('api/:version/staff/auditor/layoff','api/:version.Staff/getStaffOfAuditorsLayOff');
Route::get('api/:version/staff/agent/inter','api/:version.Staff/getInterStaff');
Route::get('api/:version/staff/agent/hire','api/:version.Staff/getHireStaff');
Route::get('api/:version/staff/agent/layoff','api/:version.Staff/getLayoffStaff');
Route::get('api/:version/staff/agent/no_job','api/:version.Staff/getNoJobStaff');
Route::get('api/:version/staff/agent/on_apply','api/:version.Staff/getOnApplyStaff');
Route::get('api/:version/staff/:id','api/:version.Staff/getStaffByID');
Route::post('api/:version/staff/agent/apply','api/:version.Staff/applyStaffToEmployment');
Route::get('api/:version/staff/employment/:id','api/:version.Staff/getStaffEmployments');
Route::post('api/:version/staff/interview','api/:version.Staff/checkInterview');
Route::post('api/:version/staff/on_job','api/:version.Staff/onJob');
Route::post('api/:version/staff/leave_job','api/:version.Staff/leaveJob');
Route::post('api/:version/staff/auditor/add','api/:version.Staff/addStaffByAuditorDirectly');
Route::post('api/:version/staff/auditor/del_staff','api/:version.Staff/delStaffByAuditor');

//takework
Route::get('api/:version/take_work','api/:version.Takework/getTakeWorks');

//commission
Route::get('api/:version/commission','api/:version.Commission/getCommissionByID');
Route::get('api/:version/commission/history','api/:version.Commission/getCommissionHistory');
Route::get('api/:version/commission/settle/:id','api/:version.Commission/settleCommission');
Route::get('api/:version/commission/money','api/:version.Commission/getAllValidCommission');
Route::get('api/:version/commission/houses','api/:version.Commission/commissionAllValid');

//token
Route::post('api/:version/get_app','api/:version.Token/getAppToken');
Route::post('api/:version/login','api/:version.Token/getUserToken');

//message
Route::post('api/:version/verify','api/:version.Message/sendVerifyCode');
Route::post('api/:version/psw_verify','api/:version.Message/sendVerifyCodeForPSW');
