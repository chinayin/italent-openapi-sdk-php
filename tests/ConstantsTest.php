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

namespace ITalentOpenSDK\Tests;

use ITalentOpenSDK\Constants;

class ConstantsTest extends TestCase
{
    public function testBasicSubsetConstants(): void
    {
        $this->assertEquals('jobhistory', Constants::SUBSET_JOB_HISTORY);
        $this->assertEquals('Education', Constants::SUBSET_EDUCATION);
        $this->assertEquals('ProjectExperience', Constants::SUBSET_PROJECT_EXPERIENCE);
        $this->assertEquals('Training', Constants::SUBSET_TRAINING);
        $this->assertEquals('Languageability', Constants::SUBSET_LANGUAGE_ABILITY);
        $this->assertEquals('Skill', Constants::SUBSET_SKILL);
        $this->assertEquals('Awards', Constants::SUBSET_AWARDS);
        $this->assertEquals('Family', Constants::SUBSET_FAMILY);
        $this->assertEquals('Certificate', Constants::SUBSET_CERTIFICATE);
        $this->assertEquals('EstimationResult', Constants::SUBSET_ESTIMATION_RESULT);
        $this->assertEquals('VocationalQualificationInfo', Constants::SUBSET_VOCATIONAL_QUALIFICATION_INFO);
        $this->assertEquals('ProfessionalTechnicalPostInfo', Constants::SUBSET_PROFESSIONAL_TECHNICAL_POST_INFO);
        $this->assertEquals('EntryMaterialRec', Constants::SUBSET_ENTRY_MATERIAL_REC);
    }

    public function testPresetSubsetConstants(): void
    {
        $this->assertEquals('PresetSubset1', Constants::SUBSET_PRESET_1);
        $this->assertEquals('PresetSubset10', Constants::SUBSET_PRESET_10);
        $this->assertEquals('PresetSubset20', Constants::SUBSET_PRESET_20);
        $this->assertEquals('PresetSubset40', Constants::SUBSET_PRESET_40);
    }

    public function testSdkConstants(): void
    {
        $this->assertEquals('1.0.0', Constants::SDK_VERSION);
        $this->assertEquals('https://openapi.italent.cn/', Constants::SDK_BASE_URI);
        $this->assertEquals(1, Constants::SDK_RETRY_MAX_RETRIES);
        $this->assertEquals('PRC', Constants::SDK_LOGGER_TIMEZONE);
    }
}
