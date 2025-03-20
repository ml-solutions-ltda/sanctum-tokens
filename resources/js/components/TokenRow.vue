<template>
  <tr>
    <td
      class="py-2 border-t border-gray-100 dark:border-gray-700 px-2 td-fit pl-5 pr-5 dark:bg-gray-800"
    ></td>
    <td
      class="px-2 py-2 border-t border-gray-100 dark:border-gray-700 whitespace-nowrap dark:bg-gray-800"
    >
      <div class="text-left text-left" via-resource="users" via-resource-id="1">
        <span class="whitespace-no-wrap">{{ token.name }}</span>
      </div>
    </td>
    <td
      v-if="showAbilities"
      class="px-2 py-2 border-t border-gray-100 dark:border-gray-700 whitespace-nowrap dark:bg-gray-800"
    >
      <div class="text-left text-left" via-resource="users" via-resource-id="1">
        <span class="whitespace-no-wrap">{{ token.abilities }}</span>
      </div>
    </td>
    <td
      class="px-2 py-2 border-t border-gray-100 dark:border-gray-700 whitespace-nowrap dark:bg-gray-800"
    >
      <div
        class="text-left text-left"
        via-resource="users"
        via-resource-id="1"
        :title="lastUsedTooltip"
      >
        <span class="whitespace-no-wrap">{{ lastUsed }}</span>
      </div>
    </td>
    <td
      class="px-2 py-2 border-t border-gray-100 dark:border-gray-700 whitespace-nowrap dark:bg-gray-800"
    >
      <div
        class="text-left text-left"
        via-resource="users"
        via-resource-id="1"
        :title="expiresTooltip"
      >
        <span class="whitespace-no-wrap">{{ expires }}</span>
      </div>
    </td>

    <td
        class="px-2 py-2 border-t border-gray-100 dark:border-gray-700 whitespace-nowrap dark:bg-gray-800"
    >
      <div class="text-left text-left" via-resource="users" via-resource-id="1">
        <span class="whitespace-no-wrap">{{ token.description || '-'}}</span>
      </div>
    </td>

    <td
      class="py-2 border-t border-gray-100 dark:border-gray-700 px-2 td-fit text-right align-middle dark:bg-gray-800"
    >
      <div class="flex items-center justify-end space-x-0 text-gray-400">
        <button
          aria-label="Delete"
          class="toolbar-button hover:text-primary-500 px-2 v-popper--has-tooltip"
          @click="showDeleteModal = true"
        >
          <Icon
              class="shrink-0 text-gray-700 dark:text-gray-400"
              name="trash"
              type="mini"
          />
        </button>

        <button>
          <Icon
              class="shrink-0 text-gray-700 dark:text-gray-400"
              name="eye"
              type="mini"
              @click="showTokenModal = true"
          />
        </button>
      </div>
    </td>
    <DeleteTokenModal
      :show="showDeleteModal"
      :token-name="token.name"
      @cancel="showDeleteModal = false"
      @confirmed="revokeToken"
    />
    <ViewTokenModal
        :show="showTokenModal"
        :token-id="token.id"
        @cancel="showTokenModal = false"
    ></ViewTokenModal>
  </tr>
</template>

<script>
import { DateTime } from "luxon";
import { Icon} from "laravel-nova-ui";

export default {
  components: {
    Icon,
  },
  props: {
    token: {
      required: true,
      type: Object,
    },
    showAbilities: {
      required: true,
      type: Boolean,
    },
  },
  emits: ["revoke-token"],
  data() {
    return {
      showDeleteModal: false,
      showTokenModal: false,
    };
  },
  computed: {
    lastUsed() {
      if (this.token.last_used_at) {
        return DateTime.fromISO(this.token.last_used_at).toRelativeCalendar();
      } else {
        return "—";
      }
    },
    lastUsedTooltip() {
      if (this.token.last_used_at) {
        return DateTime.fromISO(this.token.last_used_at).toFormat("f ZZZZ");
      } else {
        return "—";
      }
    },
    expires() {
      if (this.token.expires_at) {
        return DateTime.fromISO(this.token.expires_at).toRelativeCalendar();
      } else {
        return "N/A";
      }
    },
    expiresTooltip() {
      if (this.token.expires_at) {
        return DateTime.fromISO(this.token.expires_at).toFormat("f ZZZZ");
      } else {
        return "N/A";
      }
    },
  },
  methods: {
    revokeToken() {
      this.showDeleteModal = false;
      this.$emit("revoke-token", this.token.id);
    },
  },
};
</script>

<style lang="css" scoped></style>
