<template>
  <div id="sc-page-wrapper">
    <div id="sc-page-content">
      <ScCard>
        <ScCardTitle>
          <span>Users</span>
          <div>
            <nuxt-link
              to="/users/create"
              class="uk-button uk-button-primary"
              style="text-align: right"
            >
              Create User
            </nuxt-link>
          </div>
        </ScCardTitle>
        <ScCardBody>
          <vue-good-table
            mode="remote"
            @on-page-change="onPageChange"
            @on-sort-change="onSortChange"
            @on-column-filter="onColumnFilter"
            @on-per-page-change="onPerPageChange"
            @on-search="onSearch"
            :totalRows="totalRecords"
            :isLoading="loading"
            :pagination-options="{
              enabled: true,
            }"
            :columns="columns"
            :rows="items"
            :search-options="{
              enabled: true,
            }"
          >
            <template slot="table-row" slot-scope="props">
              <span v-if="props.column.field == 'active'">
                <div
                  class="uk-button uk-button-primary"
                  style="cursor: default !important"
                  v-if="props.row.is_active"
                >
                  Yes
                </div>
                <div
                  class="uk-button uk-button-danger"
                  style="cursor: default !important"
                  v-if="!props.row.is_active"
                >
                  No
                </div>
              </span>
              <span v-else-if="props.column.field == 'actions'">
                <button
                  class="uk-button uk-button-primary"
                  @click="edit(props.row.id)"
                >
                  Edit
                </button>
                <button
                  v-if="props.row.is_active"
                  class="uk-button uk-button-danger"
                  data-uk-toggle="target: #modal-center"
                  @click="handleDelete(props.row.id)"
                >
                  Delete
                </button>
                <button
                  v-else
                  class="uk-button black-btn"
                  data-uk-toggle="target: #modal-activate"
                  @click="handleActivate(props.row.id)"
                >
                  Activate
                </button>
              </span>
            </template>
          </vue-good-table>
        </ScCardBody>
      </ScCard>
    </div>
    <div id="modal-center" class="uk-flex-top uk-modal" data-uk-modal>
      <div
        class="
          uk-modal-dialog uk-modal-body uk-margin-auto-vertical
          text-center
        "
        style="text-align: center"
      >
        <h1>Are you sure?</h1>
        <div>
          <button
            class="uk-button uk-button-danger uk-modal-close"
            @click="deleteItem"
          >
            Delete
          </button>
          <button class="uk-button uk-button-primary uk-modal-close">
            Cancel
          </button>
        </div>
      </div>
    </div>
    <div id="modal-activate" class="uk-flex-top uk-modal" data-uk-modal>
      <div
        class="
          uk-modal-dialog uk-modal-body uk-margin-auto-vertical
          text-center
        "
        style="text-align: center"
      >
        <h1>Are you sure?</h1>
        <div>
          <button
            class="uk-button black-btn uk-modal-close"
            @click="activateUser"
          >
            Activate
          </button>
          <button class="uk-button uk-button-primary uk-modal-close">
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import "vue-good-table/dist/vue-good-table.css";
import { VueGoodTable } from "vue-good-table";

export default {
  middleware: "middleware-admin-role",
  components: { VueGoodTable },
  data() {
    return {
      items: [],
      columns: [
        {
          label: "ID",
          field: "id",
          type: "number",
        },
        {
          label: "Name",
          field: "full_name",
        },
        {
          label: "Role",
          field: "role_capitalized",
        },
        {
          label: "Email",
          field: "email",
        },
        {
          label: "Active",
          field: "active",
          sortable: false,
          html: true,
        },
        {
          label: "Actions",
          field: "actions",
          sortable: false,
          html: true,
        },
      ],
      totalRecords: 0,
      serverParams: {
        columnFilters: {},
        sortType: "",
        sortField: "",
        page: 1,
        perPage: 10,
        search: "",
      },
      rowId: null,
      loading: true,
    };
  },

  methods: {
    async deleteItem() {
      try {
        await this.$axios.post(`users/${this.rowId}`, { _method: "delete" });
        this.loadItems();
      } catch (e) {
        this.$bvToast.toast(Object.values(e.response.data.messages).join(" "), {
          title: `Error`,
          variant: "danger",
          solid: true,
          visible: true,
        });
      }
    },

    edit(id) {
      this.loading = true;
      this.rowId = id;
      this.$router.replace(`/users/${id}`);
    },

    handleDelete(id) {
      this.rowId = id;
    },

    handleActivate(id) {
      this.rowId = id;
    },

    updateParams(newProps) {
      this.serverParams = Object.assign({}, this.serverParams, newProps);
    },

    onPageChange(params) {
      this.updateParams({ page: params.currentPage });
      this.loadItems();
    },

    onPerPageChange(params) {
      this.updateParams({
        perPage: params.currentPerPage,
        page: 1,
      });
      this.loadItems();
    },

    onSortChange(params) {
      this.updateParams({
        sortType: params[0].type != "none" ? params[0].type : null,
        sortField: params[0].type != "none" ? params[0].field : null,
      });
      this.loadItems();
    },

    onColumnFilter(params) {
      this.updateParams(params);
      this.loadItems();
    },

    onSearch(params) {
      this.updateParams({
        search: params.searchTerm,
      });
      this.loadItems();
    },

    // load items is what brings back the rows from server
    async loadItems() {
      this.loading = true;
      await this.$axios
        .post(`/users-paginated`, this.serverParams)
        .then((response) => {
          this.totalRecords = response.data.pagination.total;
          this.items = response.data.data;
          this.loading = false;
        });
    },

    async activateUser() {
      try {
        await this.$axios.post(`users/${this.rowId}/activate`, {
          is_active: 1,
        });
        this.loadItems();
      } catch (e) {
        this.$bvToast.toast(Object.values(e.response.data.messages).join(" "), {
          title: `Error`,
          variant: "danger",
          solid: true,
          visible: true,
        });
      }
    },
  },

  mounted() {
    this.loadItems();
  },
};
</script>

<style scoped>
.uk-card-title {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

table {
  min-width: 675px;
}
</style>