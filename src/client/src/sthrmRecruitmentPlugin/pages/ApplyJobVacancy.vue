<!--
/**
 * Shants Tech is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 Shants Tech Inc., http://www.shants-tech.com
 *
 * Shants Tech is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * Shants Tech is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */
 -->

<template>
  <div class="sthrm-container">
    <div class="sthrm-card-container">
      <div class="sthrm-card-container-header">
        <oxd-text class="sthrm-main-title" tag="h6">
          {{
            $t('recruitment.apply_for_n_vacancy', {
              vacancyName: vacancyName,
            })
          }}
        </oxd-text>
        <img class="oxd-brand-banner" :src="bannerSrc" />
      </div>
      <template v-if="vacancyDescription">
        <oxd-divider />
        <oxd-text class="sthrm-vacancy-description" tag="p">
          {{ $t('general.description') }}
        </oxd-text>
        <oxd-text tag="p" :class="descriptionClasses">
          <pre class="sthrm-applicant-card-pre-tag"
            >{{ vacancyDescription }}
        </pre
          >
        </oxd-text>
        <div
          v-if="vacancyDescription.length > descriptionLength"
          class="sthrm-vacancy-card-footer"
        >
          <oxd-text
            tag="p"
            class="sthrm-vacancy-card-anchor-tag"
            @click="onToggleMore"
          >
            {{
              isViewDetails ? $t('general.show_less') : $t('general.show_more')
            }}
          </oxd-text>
        </div>
      </template>
      <oxd-divider />
      <oxd-form
        ref="applicantForm"
        method="post"
        enctype="multipart/form-data"
        :loading="isLoading"
        :action="submitUrl"
        @submitValid="onSave"
      >
        <input name="_token" :value="token" type="hidden" />
        <input name="vacancyId" :value="vacancyId" type="hidden" />
        <div class="sthrm-applicant-container">
          <oxd-form-row class="sthrm-applicant-container-row">
            <oxd-grid :cols="1" class="sthrm-full-width-grid">
              <oxd-grid-item>
                <full-name-input
                  v-model:firstName="applicant.firstName"
                  v-model:lastName="applicant.lastName"
                  v-model:middleName="applicant.middleName"
                  :label="$t('general.full_name')"
                  :rules="rules"
                  required
                />
              </oxd-grid-item>
            </oxd-grid>
          </oxd-form-row>
          <oxd-form-row class="sthrm-applicant-container-row">
            <oxd-grid :cols="3" class="sthrm-full-width-grid">
              <oxd-grid-item class="sthrm-applicant-container-colspan-2">
                <oxd-input-field
                  v-model="applicant.email"
                  name="email"
                  :label="$t('general.email')"
                  :placeholder="$t('general.type_here')"
                  :rules="rules.email"
                  required
                />
              </oxd-grid-item>
              <oxd-grid-item>
                <oxd-input-field
                  v-model="applicant.contactNumber"
                  name="contactNumber"
                  :label="$t('recruitment.contact_number')"
                  :placeholder="$t('general.type_here')"
                  :rules="rules.contactNumber"
                />
              </oxd-grid-item>
            </oxd-grid>
          </oxd-form-row>
          <oxd-form-row>
            <oxd-grid :cols="3" class="sthrm-full-width-grid">
              <oxd-grid-item>
                <oxd-input-field
                  v-model="applicant.resume"
                  name="resume"
                  type="file"
                  :label="$t('recruitment.resume')"
                  :button-label="$t('general.browse')"
                  :rules="rules.resume"
                  required
                />
                <oxd-text class="sthrm-input-hint" tag="p">
                  {{ $t('general.accept_custom_format_file') }}
                </oxd-text>
              </oxd-grid-item>
            </oxd-grid>
          </oxd-form-row>
          <oxd-form-row>
            <oxd-grid :cols="3" class="sthrm-full-width-grid">
              <oxd-grid-item class="sthrm-applicant-container-colspan-2">
                <oxd-input-field
                  v-model="applicant.keywords"
                  name="keywords"
                  :label="$t('recruitment.keywords')"
                  :placeholder="
                    `${$t('recruitment.enter_comma_seperated_words')}...`
                  "
                  :rules="rules.keywords"
                />
              </oxd-grid-item>
            </oxd-grid>
          </oxd-form-row>
          <oxd-form-row>
            <oxd-grid :cols="3" class="sthrm-full-width-grid">
              <oxd-grid-item class="sthrm-applicant-container-colspan-2">
                <oxd-input-field
                  v-model="applicant.comment"
                  name="comment"
                  :label="$t('general.notes')"
                  type="textarea"
                  :placeholder="$t('general.type_here')"
                  :rules="rules.comment"
                />
              </oxd-grid-item>
            </oxd-grid>
          </oxd-form-row>
          <oxd-form-row>
            <oxd-grid :cols="3" class="sthrm-full-width-grid">
              <oxd-grid-item
                class="sthrm-applicant-container-colspan-2 sthrm-applicant-container-grid-checkbox"
              >
                <oxd-input-field
                  v-model="applicant.consentToKeepData"
                  name="consentToKeepData"
                  :label="$t('recruitment.consent_to_keep_data')"
                  type="checkbox"
                />
              </oxd-grid-item>
            </oxd-grid>
          </oxd-form-row>
          <oxd-divider />
          <oxd-form-actions>
            <required-text />
            <oxd-button
              :label="$t('general.back')"
              display-type="ghost"
              @click="onCancel"
            />
            <submit-button :label="$t('general.submit')" />
          </oxd-form-actions>
        </div>
      </oxd-form>
    </div>
  </div>
  <div class="sthrm-paper-container">
    <oxd-text tag="p" class="sthrm-vacancy-list-poweredby">
      {{ $t('recruitment.powered_by') }}
    </oxd-text>
    <img
      :src="defaultPic"
      alt="Shants Tech Picture"
      class="sthrm-container-img"
    />
    <slot name="footer"></slot>
  </div>
  <success-dialogue ref="showDialogueModal"></success-dialogue>
</template>

<script>
import {ref, toRefs} from 'vue';
import FullNameInput from '@/sthrmPimPlugin/components/FullNameInput';
import SuccessDialog from '@/sthrmRecruitmentPlugin/components/SuccessDialog';
import {
  maxFileSize,
  required,
  shouldNotExceedCharLength,
  validEmailFormat,
  validFileTypes,
  validPhoneNumberFormat,
} from '@ohrm/core/util/validation/rules';
import SubmitButton from '@/core/components/buttons/SubmitButton';
import {navigate} from '@/core/util/helper/navigation';
import {APIService} from '@/core/util/services/api.service';
import {urlFor} from '@/core/util/helper/url';
import useResponsive from '@ohrm/oxd/composables/useResponsive';

const applicantModel = {
  firstName: '',
  middleName: '',
  lastName: '',
  contactNumber: '',
  email: '',
  consentToKeepData: false,
  resume: null,
  keywords: null,
  comment: null,
};

export default {
  name: 'ApplyJobVacancy',
  components: {
    'submit-button': SubmitButton,
    'full-name-input': FullNameInput,
    'success-dialogue': SuccessDialog,
  },
  props: {
    allowedFileTypes: {
      type: Array,
      required: true,
    },
    maxFileSize: {
      type: Number,
      required: true,
    },
    vacancyId: {
      type: Number,
      required: true,
    },
    success: {
      type: Boolean,
      default: false,
    },
    bannerSrc: {
      type: String,
      required: true,
    },
    token: {
      type: String,
      required: true,
    },
  },
  setup() {
    const defaultPic = `${window.appGlobal.baseUrl}/../images/ohrm_branding.png`;
    const applicant = ref({
      ...applicantModel,
    });
    const responsiveState = useResponsive();
    const http = new APIService(
      window.appGlobal.baseUrl,
      'api/v2/recruitment/public/vacancies',
    );

    return {
      http,
      applicant,
      defaultPic,
      ...toRefs(responsiveState),
    };
  },
  data() {
    return {
      title: null,
      subtitle: null,
      successLabel: null,
      isLoading: false,
      vacancyName: '',
      vacancyDescription: null,
      rules: {
        firstName: [required, shouldNotExceedCharLength(30)],
        middleName: [shouldNotExceedCharLength(30)],
        lastName: [required, shouldNotExceedCharLength(30)],
        resume: [
          required,
          maxFileSize(this.maxFileSize),
          validFileTypes(this.allowedFileTypes),
        ],
        comment: [shouldNotExceedCharLength(250)],
        keywords: [shouldNotExceedCharLength(250)],
        contactNumber: [shouldNotExceedCharLength(25), validPhoneNumberFormat],
        email: [required, validEmailFormat, shouldNotExceedCharLength(50)],
      },
      isViewDetails: true,
    };
  },
  computed: {
    submitUrl() {
      return urlFor('/recruitment/public/applicants');
    },
    descriptionClasses() {
      return {
        'sthrm-vacancy-description': true,
        'sthrm-vacancy-card-body': !this.isViewDetails,
      };
    },
    isMobile() {
      return this.windowWidth < 600;
    },
    descriptionLength() {
      if (this.isMobile) return 150;
      return this.windowWidth < 1920 ? 250 : 400;
    },
  },
  beforeMount() {
    this.http.get(this.vacancyId).then(response => {
      const {data} = response.data;
      this.vacancyName = data?.name ?? '';
      this.vacancyDescription = data?.description;
    });
  },
  mounted() {
    if (this.success) {
      this.showDialogue();
    }
  },
  methods: {
    onSave() {
      this.$refs.applicantForm.$el.submit();
    },
    onCancel() {
      navigate('/recruitmentApply/jobs.html');
    },
    showDialogue() {
      this.$refs.showDialogueModal.showSuccessDialog().then(confirmation => {
        if (confirmation === 'ok') {
          navigate('/recruitmentApply/jobs.html');
        }
      });
    },
    onToggleMore() {
      this.isViewDetails = !this.isViewDetails;
    },
  },
};
</script>

<style src="./public-job-vacancy.scss" lang="scss" scoped></style>
