<template>
  <Modal :show="show" @close-via-escape="$emit('cancel')">
    <div
        class="mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden"
    >
      <slot>
        <ModalHeader>{{ __("Token show") }}</ModalHeader>
        <ModalContent>
          <div class="flex flex-col">
            <div v-if="token" class="flex flex-col space-y-2">
              <div class="flex items-center justify-between space-x-2">
                <input
                    readonly
                    class="w-full form-control form-input"
                    type="text"
                    :value="token"
                    data-disabled
                />
                <Button
                    type="button"
                    variant="action"
                    @click.prevent.stop="copyValueToClipboard(token)"
                >
                  <Icon
                      v-tooltip="{
                      content: __('Copied to clipboard'),
                      triggers: ['click'],
                      placement: 'right',
                      autoHide: true,
                    }"
                      name="clipboard"
                      type="solid"
                  />
                </Button>
              </div>
            </div>
          </div>
        </ModalContent>
      </slot>

      <ModalFooter>
        <div class="ml-auto">
          <NovaButton
              type="button"
              class="mr-4"
              @click.prevent="$emit('cancel')"
          >
            {{ __("Close") }}
          </NovaButton>
          <NovaButton
              type="button"
              class="mr-4"
              @click="showToken"
          >
            {{ __("Show") }}
          </NovaButton>
        </div>
      </ModalFooter>
    </div>
  </Modal>
</template>

<script>
import {Button as NovaButton, Icon} from "laravel-nova-ui";
import {copyValueToClipboard} from "../utils";
import {REVEAL_PATH} from "../api";

export default {
  components: {
    NovaButton,
    Icon,
  },
  props: {
    tokenId: {
      required: true,
      type: Number,
    },
    show: {type: Boolean, default: false},
  },
  emits: ["confirmed", "cancel"],
  data() {
    return {
      token: '',
      showTokenModal: false,
    }
  },
  methods: {
    copyValueToClipboard,
    showToken() {
      Nova.request()
          .get(REVEAL_PATH + `${this.tokenId}`)
          .then((response) => {
            this.token = response.data.token;
          });
    },
  },
};
</script>
