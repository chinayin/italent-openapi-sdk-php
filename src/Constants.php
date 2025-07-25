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

namespace ITalentOpenSDK;

class Constants
{
    /** @var string sdk版本号 */
    public const SDK_VERSION = '1.0.0';
    /** @var string 调用base uri */
    public const SDK_BASE_URI = 'https://openapi.italent.cn/';
    /** @var int 重试次数 */
    public const SDK_RETRY_MAX_RETRIES = 1;
    /** @var string 日志时区 */
    public const SDK_LOGGER_TIMEZONE = "PRC";

    /** 业务数据常量 - 子集类型 */

    // 基础子集
    public const SUBSET_JOB_HISTORY = 'jobhistory'; // 就业经历
    public const SUBSET_EDUCATION = 'Education'; // 教育经历
    public const SUBSET_PROJECT_EXPERIENCE = 'ProjectExperience'; // 项目经历
    public const SUBSET_TRAINING = 'Training'; // 培训经历
    public const SUBSET_LANGUAGE_ABILITY = 'Languageability'; // 语言能力
    public const SUBSET_SKILL = 'Skill'; // 专业技能
    public const SUBSET_AWARDS = 'Awards'; // 表彰与奖励
    public const SUBSET_FAMILY = 'Family'; // 家庭成员
    public const SUBSET_CERTIFICATE = 'Certificate'; // 证书执照
    public const SUBSET_ESTIMATION_RESULT = 'EstimationResult'; // 考核结果
    public const SUBSET_VOCATIONAL_QUALIFICATION_INFO = 'VocationalQualificationInfo'; // 执业（职业）资格信息
    public const SUBSET_PROFESSIONAL_TECHNICAL_POST_INFO = 'ProfessionalTechnicalPostInfo'; // 专业技术职务
    public const SUBSET_ENTRY_MATERIAL_REC = 'EntryMaterialRec'; // 材料管理

    // 预制子集1-40
    public const SUBSET_PRESET_1 = 'PresetSubset1'; // 预制子集1
    public const SUBSET_PRESET_2 = 'PresetSubset2'; // 预制子集2
    public const SUBSET_PRESET_3 = 'PresetSubset3'; // 预制子集3
    public const SUBSET_PRESET_4 = 'PresetSubset4'; // 预制子集4
    public const SUBSET_PRESET_5 = 'PresetSubset5'; // 预制子集5
    public const SUBSET_PRESET_6 = 'PresetSubset6'; // 预制子集6
    public const SUBSET_PRESET_7 = 'PresetSubset7'; // 预制子集7
    public const SUBSET_PRESET_8 = 'PresetSubset8'; // 预制子集8
    public const SUBSET_PRESET_9 = 'PresetSubset9'; // 预制子集9
    public const SUBSET_PRESET_10 = 'PresetSubset10'; // 预制子集10
    public const SUBSET_PRESET_11 = 'PresetSubset11'; // 预制子集11
    public const SUBSET_PRESET_12 = 'PresetSubset12'; // 预制子集12
    public const SUBSET_PRESET_13 = 'PresetSubset13'; // 预制子集13
    public const SUBSET_PRESET_14 = 'PresetSubset14'; // 预制子集14
    public const SUBSET_PRESET_15 = 'PresetSubset15'; // 预制子集15
    public const SUBSET_PRESET_16 = 'PresetSubset16'; // 预制子集16
    public const SUBSET_PRESET_17 = 'PresetSubset17'; // 预制子集17
    public const SUBSET_PRESET_18 = 'PresetSubset18'; // 预制子集18
    public const SUBSET_PRESET_19 = 'PresetSubset19'; // 预制子集19
    public const SUBSET_PRESET_20 = 'PresetSubset20'; // 预制子集20
    public const SUBSET_PRESET_21 = 'PresetSubset21'; // 预制子集21
    public const SUBSET_PRESET_22 = 'PresetSubset22'; // 预制子集22
    public const SUBSET_PRESET_23 = 'PresetSubset23'; // 预制子集23
    public const SUBSET_PRESET_24 = 'PresetSubset24'; // 预制子集24
    public const SUBSET_PRESET_25 = 'PresetSubset25'; // 预制子集25
    public const SUBSET_PRESET_26 = 'PresetSubset26'; // 预制子集26
    public const SUBSET_PRESET_27 = 'PresetSubset27'; // 预制子集27
    public const SUBSET_PRESET_28 = 'PresetSubset28'; // 预制子集28
    public const SUBSET_PRESET_29 = 'PresetSubset29'; // 预制子集29
    public const SUBSET_PRESET_30 = 'PresetSubset30'; // 预制子集30
    public const SUBSET_PRESET_31 = 'PresetSubset31'; // 预制子集31
    public const SUBSET_PRESET_32 = 'PresetSubset32'; // 预制子集32
    public const SUBSET_PRESET_33 = 'PresetSubset33'; // 预制子集33
    public const SUBSET_PRESET_34 = 'PresetSubset34'; // 预制子集34
    public const SUBSET_PRESET_35 = 'PresetSubset35'; // 预制子集35
    public const SUBSET_PRESET_36 = 'PresetSubset36'; // 预制子集36
    public const SUBSET_PRESET_37 = 'PresetSubset37'; // 预制子集37
    public const SUBSET_PRESET_38 = 'PresetSubset38'; // 预制子集38
    public const SUBSET_PRESET_39 = 'PresetSubset39'; // 预制子集39
    public const SUBSET_PRESET_40 = 'PresetSubset40'; // 预制子集40

}
