<template>
  <Modal :show="show">
    <div
      class="mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden"
    >
      <slot>
        <ModalHeader>{{ __("Created Token") }}</ModalHeader>
        <ModalContent>
          <div class="flex flex-col">
            <div class="flex flex-col space-y-2">
              <div class="flex items-center justify-between space-x-2">
                <input
                  readonly
                  class="w-full form-control form-input"
                  type="text"
                  :value="newToken"
                  data-disabled
                />
                <Button
                    type="button"
                    variant="action"
                    @click.prevent.stop="copyValueToClipboard(newToken)"
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
          <NovaButton type="button" @click.prevent="handleConfirmed">
          {{ __("Confirm") }}
          </NovaButton>
        </div>
      </ModalFooter>
    </div>
  </Modal>
</template>

<script>
import { Button as NovaButton, Icon } from "laravel-nova-ui";
import {copyValueToClipboard} from "../utils";

export default {
  components: {
    NovaButton,
    Icon,
  },
  props: {
    newToken: {
      required: true,
      type: String,
    },
    show: { type: Boolean, default: false },
  },
  emits: ["confirmed"],
  methods: {
    copyValueToClipboard,
    handleConfirmed() {
      this.$emit("confirmed");
    },
  },
};
</script>
