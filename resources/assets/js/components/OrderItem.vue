<template>
  <tr>
    <td><button type="button" class="btn btn-outline-secondary" @click="$emit('on-delete', orderItem.id)">-</button></td>
    <td>
      <div class="row">
        <div class="col">
          {{ orderItem.product.family.compound_id }}
        </div>
        <div class="col" v-html="orderItem.product.family.compound">
        </div>
        <div class="col">
          {{ orderItem.product.type }} {{ orderItem.product.amount }} {{ orderItem.product.amount_units }}
        </div>
        <div class="col">
          {{ orderItem.lot_number }}
        </div>
      </div>
    </td>
    <td>
      <input type="text" class="form-control" v-model="orderItem.price">
    </td>
    <td>
      <div class="row">
        <div class="col-sm-4">
          <input type="number" class="form-control" v-model="orderItem.quantity">
        </div>
        <div class="col-sm-1">
          <input type="button" class="btn btn-outline-secondary btn-sm" orderItem="-" :disabled="orderItem.quantity <= 1" @click="onUpdateItemQuantity(-1)">
        </div>
        <div class="col-sm-1">
          <input type="button" class="btn btn-outline-secondary btn-sm" orderItem="+" @click="onUpdateItemQuantity(1)">
        </div>
      </div>
    </td>
    <td>
      <div class="form-check">
        <input :disabled="!orderItem.product.msds" :checked="orderItem.product.msds && orderItem.includes_msds === 1" class="form-check-input" type="checkbox" v-model="orderItem.includes_msds" true-value="1" false-value="0">
      </div>
    </td>
    <td>
      <div class="form-check">
        <input :disabled="!orderItem.cofa" :checked="orderItem.cofa && orderItem.includes_cofa === 1" class="form-check-input" type="checkbox" v-model="orderItem.includes_cofa" true-value="1" false-value="0">
      </div>
    </td>
  </tr>
</template>

<script>
  export default {
    props: {
      value: {
        type: Object,
        required: true
      }
    },
    data() {
      return {};
    },

    methods: {
      onUpdateItemQuantity(change) {
        this.orderItem.quantity += change;
      },

      onRemoveItem() {
        this.$emit('on-delete', this.orderItem.id);
      }
    },

    watch: {
      orderItem() {
        this.$emit('input', this.orderItem);
      }
    }
  }
</script>
