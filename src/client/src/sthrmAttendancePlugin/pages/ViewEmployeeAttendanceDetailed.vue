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
  <oxd-table-filter
    :filter-title="$t('attendance.employee_attendance_records')"
  >
    <oxd-form @submitValid="onClickView">
      <oxd-form-row>
        <oxd-grid :cols="4" class="sthrm-full-width-grid">
          <oxd-grid-item>
            <employee-autocomplete
              v-model="filters.employee"
              :rules="rules.employee"
              :params="{
                includeEmployees: 'currentAndPast',
              }"
            />
          </oxd-grid-item>
          <oxd-grid-item>
            <date-input
              v-model="filters.date"
              :rules="rules.date"
              :years="yearArray"
              :label="$t('general.date')"
              required
            />
          </oxd-grid-item>
        </oxd-grid>
      </oxd-form-row>

      <oxd-divider />

      <oxd-form-actions>
        <required-text />
        <oxd-button
          display-type="secondary"
          :label="$t('general.view')"
          type="submit"
        />
      </oxd-form-actions>
    </oxd-form>
  </oxd-table-filter>
  <br />
  <div class="sthrm-paper-container">
    <div
      v-if="isEditable && filters.employee"
      class="sthrm-header-container"
    >
      <oxd-button
        icon-name="plus"
        display-type="secondary"
        :label="$t('general.add')"
        @click="onClickAdd"
      />
      <oxd-text class="sthrm-header-total" tag="span">
        {{ $t('time.total_duration') }}: {{ totalDuration }}
      </oxd-text>
    </div>
    <table-header
      :total="total"
      :loading="isLoading"
      :show-divider="isEditable"
      :selected="checkedItems.length"
      @delete="onClickDeleteSelected"
    ></table-header>
    <div class="sthrm-container">
      <oxd-card-table
        v-model:selected="checkedItems"
        :headers="headers"
        :items="items?.data"
        :selectable="isEditable"
        :clickable="false"
        :loading="isLoading"
        row-decorator="oxd-table-decorator-card"
      />
    </div>
    <div class="sthrm-bottom-container">
      <oxd-pagination
        v-if="showPaginator"
        v-model:current="currentPage"
        :length="pages"
      />
    </div>
    <delete-confirmation ref="deleteDialog"></delete-confirmation>
  </div>
</template>

<script>
import {computed, ref} from 'vue';
import {
  required,
  validSelection,
  validDateFormat,
} from '@/core/util/validation/rules';
import {navigate} from '@/core/util/helper/navigation';
import {APIService} from '@/core/util/services/api.service';
import usePaginate from '@ohrm/core/util/composable/usePaginate';
import {freshDate, formatDate, parseDate} from '@ohrm/core/util/helper/datefns';
import RecordCell from '@/sthrmAttendancePlugin/components/RecordCell.vue';
import EmployeeAutocomplete from '@/core/components/inputs/EmployeeAutocomplete';
import DeleteConfirmationDialog from '@ohrm/components/dialogs/DeleteConfirmationDialog';
import {yearRange} from '@/core/util/helper/year-range';
import {getStandardTimezone} from '@/core/util/helper/datefns';
import useDateFormat from '@/core/util/composable/useDateFormat';
import useLocale from '@/core/util/composable/useLocale';

export default {
  components: {
    'employee-autocomplete': EmployeeAutocomplete,
    'delete-confirmation': DeleteConfirmationDialog,
  },

  props: {
    date: {
      type: String,
      default: null,
    },
    employee: {
      type: Object,
      required: true,
    },
    isEditable: {
      type: Boolean,
      default: false,
    },
  },

  setup(props) {
    const filters = ref({
      date: props.date ? props.date : formatDate(freshDate(), 'yyyy-MM-dd'),
      employee: props.employee
        ? {
            id: props.employee.empNumber,
            label: `${props.employee.firstName} ${props.employee.middleName} ${props.employee.lastName}`,
            isPastEmployee: props.employee.terminationId,
          }
        : null,
    });

    const serializedFilters = computed(() => {
      return {
        date: filters.value.date,
        empNumber: filters.value.employee?.id,
      };
    });

    const http = new APIService(
      window.appGlobal.baseUrl,
      `api/v2/attendance/employees/${props.employee.empNumber}/records`,
    );
    const {locale} = useLocale();
    const {jsDateFormat, userDateFormat} = useDateFormat();

    const rules = {
      date: [required, validDateFormat(userDateFormat)],
      employee: [validSelection],
    };

    const attendanceRecordNormalizer = data => {
      return data.map(item => {
        const {punchIn, punchOut} = item;
        const punchInDate = formatDate(
          parseDate(punchIn?.userDate),
          jsDateFormat,
          {locale},
        );
        const punchOutDate = formatDate(
          parseDate(punchOut?.userDate),
          jsDateFormat,
          {locale},
        );

        return {
          id: item.id,
          punchIn: {
            ...punchIn,
            userDate: punchInDate,
          },
          punchOut: {
            ...punchOut,
            userDate: punchOutDate,
          },
          punchInNote: punchIn.note,
          punchOutNote: punchOut.note,
          duration: item.duration,
        };
      });
    };

    const {
      total,
      pages,
      pageSize,
      response,
      isLoading,
      execQuery,
      currentPage,
      showPaginator,
    } = usePaginate(http, {
      query: serializedFilters,
      normalizer: attendanceRecordNormalizer,
      prefetch: true,
    });

    const totalDuration = computed(() => {
      const meta = response.value?.meta;
      return meta ? meta.sum.label : '0.00';
    });

    return {
      http,
      rules,
      total,
      pages,
      filters,
      pageSize,
      isLoading,
      execQuery,
      currentPage,
      showPaginator,
      items: response,
      totalDuration,
    };
  },

  data() {
    return {
      yearArray: [...yearRange()],
      headers: [
        {
          name: 'punchIn',
          slot: 'title',
          title: this.$t('attendance.punch_in'),
          style: {flex: 1},
          cellRenderer: this.cellRenderer,
        },
        {
          name: 'punchInNote',
          slot: 'title',
          title: this.$t('attendance.punch_in_note'),
          style: {flex: 1},
        },
        {
          name: 'punchOut',
          slot: 'title',
          title: this.$t('attendance.punch_out'),
          style: {flex: 1},
          cellRenderer: this.cellRenderer,
        },
        {
          name: 'punchOutNote',
          slot: 'title',
          title: this.$t('attendance.punch_out_note'),
          style: {flex: 1},
        },
        {
          name: 'duration',
          slot: 'title',
          title: this.$t('attendance.duration_hours'),
          style: {flex: 1},
        },
        {
          ...(this.isEditable && {
            name: 'actions',
            title: this.$t('general.actions'),
            slot: 'action',
            style: {flex: 1},
            cellType: 'oxd-table-cell-actions',
            cellConfig: {
              delete: {
                onClick: this.onClickDelete,
                component: 'oxd-icon-button',
                props: {
                  name: 'trash',
                },
              },
              edit: {
                onClick: this.onClickEdit,
                props: {
                  name: 'pencil-fill',
                },
              },
            },
          }),
        },
      ],
      checkedItems: [],
    };
  },

  methods: {
    cellRenderer(...args) {
      const cellData = args[1];
      return {
        component: RecordCell,
        props: {
          date: cellData.userDate,
          time: cellData.userTime,
          offset: getStandardTimezone(cellData.offset),
        },
      };
    },
    onClickDeleteSelected() {
      const ids = this.checkedItems.map(index => {
        return this.items?.data[index].id;
      });
      this.$refs.deleteDialog.showDialog().then(confirmation => {
        if (confirmation === 'ok') {
          this.deleteItems(ids);
        }
      });
    },
    onClickDelete(item) {
      this.$refs.deleteDialog.showDialog().then(confirmation => {
        if (confirmation === 'ok') {
          this.deleteItems([item.id]);
        }
      });
    },
    deleteItems(items) {
      if (items instanceof Array) {
        this.isLoading = true;
        this.http
          .deleteAll({
            ids: items,
          })
          .then(() => {
            return this.$toast.deleteSuccess();
          })
          .then(() => {
            this.isLoading = false;
            this.resetDataTable();
          });
      }
    },
    async resetDataTable() {
      this.checkedItems = [];
      await this.execQuery();
    },
    onClickView() {
      return navigate('/attendance/viewAttendanceRecord', undefined, {
        employeeId: this.filters.employee?.id,
        date: this.filters?.date,
      });
    },
    onClickAdd() {
      return navigate('/attendance/proxyPunchInPunchOut', undefined, {
        employeeId: this.filters.employee?.id,
        date: this.filters?.date,
      });
    },
    onClickEdit(item) {
      return navigate('/attendance/editEmployeeAttendanceRecord/{id}', {
        id: item.id,
      });
    },
  },
};
</script>
