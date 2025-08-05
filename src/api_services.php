<?php

declare(strict_types=1);

/*
 * This file is part of the ITalent OpenAPI SDK for PHP.
 *
 * (c) chinayin <whereismoney@qq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use ITalentOpenSDK\Api as ITalentApi;

return [
    'openapi' => ITalentApi\OpenApi::class,
    // 组织员工
    'TenantBase.Organization' => ITalentApi\TenantBaseExternal\Organization::class,
    'TenantBase.Employee' => ITalentApi\TenantBaseExternal\Employee::class,
    'TenantBase.EmployeeSubset' => ITalentApi\TenantBaseExternal\EmployeeSubset::class,
    'TenantBase.JobPost' => ITalentApi\TenantBaseExternal\JobPost::class,
    // 假勤管理
    'AttendanceOpen.Vacation' => ITalentApi\AttendanceOpen\Vacation::class,
    'AttendanceOpen.VacationRemain' => ITalentApi\AttendanceOpen\VacationRemain::class,
    'AttendanceOpen.WorkShift' => ITalentApi\AttendanceOpen\WorkShift::class,
    'AttendanceOpen.WorkShiftRecord' => ITalentApi\AttendanceOpen\WorkShiftRecord::class,
];
