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
  <div class="sthrm-background-container">
    <div class="sthrm-paper-container">
      <div class="sthrm-header-container">
        <oxd-text tag="h6" class="sthrm-main-title">
          {{ $t('admin.organization_structure') }}
        </oxd-text>
        <oxd-switch-input
          v-if="!isLoading"
          v-model="editable"
          :option-label="$t('general.edit')"
          label-position="left"
        />
      </div>
      <oxd-divider
        v-if="!isLoading"
        class="sthrm-horizontal-margin sthrm-clear-margins"
      />
      <div v-if="!isLoading" class="org-root-container">
        <oxd-text
          tag="p"
          :class="{
            '--parent': data && data.children != 0,
          }"
        >
          {{ data.name }}
        </oxd-text>
        <oxd-button
          v-show="editable"
          class="org-structure-add"
          :label="$t('general.add')"
          icon-name="plus"
          display-type="secondary"
          @click="onAddOrglevel(data)"
        />
      </div>
      <div class="org-container">
        <div v-if="isLoading" class="loader">
          <oxd-loading-spinner />
        </div>
        <oxd-tree-view
          v-else
          :data="data"
          :open="true"
          :show-root="false"
          class="org-structure"
        >
          <template #content="{nodeData}">
            <oxd-sheet
              type="pastel-white"
              :class="{
                'org-structure-card': true,
                '--edit': editable,
              }"
            >
              <div class="org-name">
                {{ nodeData.unitId ? nodeData.unitId + ':' : '' }}
                &nbsp;{{ nodeData.name }}
              </div>
              <div v-show="editable" class="org-action">
                <oxd-icon-button
                  class="org-action-icon"
                  name="trash-fill"
                  role="none"
                  @click="onDelete(nodeData)"
                />
                <oxd-icon-button
                  class="org-action-icon"
                  name="pencil-fill"
                  role="none"
                  @click="onEditOrglevel(nodeData)"
                />
                <oxd-icon-button
                  class="org-action-icon"
                  name="plus"
                  role="none"
                  @click="onAddOrglevel(nodeData)"
                />
              </div>
            </oxd-sheet>
          </template>
        </oxd-tree-view>
      </div>
    </div>
    <delete-confirmation ref="deleteDialog"></delete-confirmation>
    <save-org-unit
      v-if="showSaveModal"
      :data="saveModalState"
      @close="onSaveModalClose"
    ></save-org-unit>
    <edit-org-unit
      v-if="showEditModal"
      :data="editModalState"
      @close="onEditModalClose"
    ></edit-org-unit>
  </div>
</template>

<script>
import {APIService} from '@/core/util/services/api.service';
import TreeView from '@ohrm/oxd/core/components/TreeView/TreeView';
import Sheet from '@ohrm/oxd/core/components/Sheet/Sheet';
import SwitchInput from '@ohrm/oxd/core/components/Input/SwitchInput';
import Spinner from '@ohrm/oxd/core/components/Loader/Spinner';
import DeleteConfirmationDialog from '@ohrm/components/dialogs/DeleteConfirmationDialog';
import SaveOrgUnit from './SaveOrgUnit';
import EditOrgUnit from './EditOrgUnit';

export default {
  components: {
    'oxd-tree-view': TreeView,
    'oxd-sheet': Sheet,
    'oxd-switch-input': SwitchInput,
    'oxd-loading-spinner': Spinner,
    'delete-confirmation': DeleteConfirmationDialog,
    'save-org-unit': SaveOrgUnit,
    'edit-org-unit': EditOrgUnit,
  },
  setup() {
    const http = new APIService(
      window.appGlobal.baseUrl,
      'api/v2/admin/subunits',
    );
    return {
      http,
    };
  },
  data() {
    return {
      isLoading: false,
      editable: false,
      showSaveModal: false,
      saveModalState: null,
      showEditModal: false,
      editModalState: null,
      data: {},
    };
  },

  created() {
    this.fetchOrgStructure();
  },
  methods: {
    onDelete(node) {
      this.$refs.deleteDialog.showDialog().then(confirmation => {
        if (confirmation === 'ok') {
          this.isLoading = true;
          this.http
            .delete(node.id)
            .then(() => {
              return this.$toast.deleteSuccess();
            })
            .then(() => {
              this.isLoading = false;
              this.fetchOrgStructure();
            });
        }
      });
    },
    onAddOrglevel(node) {
      if (this.editable) {
        this.saveModalState = node;
        this.showSaveModal = true;
      }
    },
    onEditOrglevel(node) {
      if (this.editable) {
        this.editModalState = node;
        this.showEditModal = true;
      }
    },
    onSaveModalClose() {
      this.saveModalState = null;
      this.showSaveModal = false;
      this.fetchOrgStructure();
    },
    onEditModalClose() {
      this.editModalState = null;
      this.showEditModal = false;
      this.fetchOrgStructure();
    },
    fetchOrgStructure() {
      this.isLoading = true;
      this.http
        .getAll({
          mode: 'tree',
        })
        .then(response => {
          const {data} = response.data;
          this.data = data[0];
        })
        .finally(() => {
          this.isLoading = false;
        });
    },
  },
};
</script>

<style src="./org-structure.scss" lang="scss" scoped></style>

<style lang="scss">
.oxd-tree-node-content {
  width: 100%;
}
.oxd-tree-node-toggle {
  & .oxd-icon-button {
    background-color: $oxd-white-color !important;
  }
}
</style>
