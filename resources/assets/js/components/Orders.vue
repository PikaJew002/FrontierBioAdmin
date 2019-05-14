<template>
  <div class="container">
    <b-alert :show="message.countDown"
             dismissible
             :variant="message.type"
             fade
             @dismissed="message.countDown=0"
             @dismiss-count-down="countDownChanged">
      {{message.message}}
    </b-alert>
    <h1>Order Management</h1>
    <button v-if="!showCreate" @click="toggleForm()" class="btn btn-outline-primary">Create Order</button>
    <div v-if="showCreate" class="card bg-light mt-3">
      <div class="card-header">{{ formTitle }} Order</div>
      <div class="card-body">
        <form @submit.prevent="onSubmit(order)">
          <div class="form-group">
            <select class="form-control"
                    :class="{ 'is-invalid': $v.order.contact_id.$invalid && !$v.order.contact_id.$pending,
                              'is-valid': !$v.order.contact_id.$invalid && !$v.order.contact_id.$pending }"
                    v-model.number="order.contact_id"
                    @change="onChangeContact()">
              <option selected value="0">Select a contact</option>
              <option v-for="contact in entities['Contact']" :value="contact.id">
                {{ contact.prefix }} {{ contact.first_name }} {{ contact.last_name }} ({{ contact.customer.name }})
              </option>
            </select>
            <div v-if="!$v.order.contact_id.required || !$v.order.contact_id.minValue" class="invalid-feedback">
              Contact selection is required!
            </div>
          </div>
          <div class="row" v-if="order.contact && order.contact_id != 0">
            <div class="col form-group">
              <label>Phone</label>
              <input type="text" class="form-control" :value="order.contact.phone" disabled>
            </div>
            <div class="col form-group">
              <label>Email</label>
              <input type="text" class="form-control" :value="order.contact.email" disabled>
            </div>
          </div>
          <div class="row">
            <div class="col form-group">
              <label for="shipping_address">Shipping Address</label>
              <div :style="{ border: shippingAddressBorder }">
                <vue-ckeditor id="shipping_address"
                              v-model="order.shipping_address"
                              :config="config"
                              @blur="onBlur($event)"
                              @focus="onFocus($event)" />
              </div>
              <div v-if="!$v.order.shipping_address.required">
                <small style="color: red">Shipping Address required!</small>
              </div>
              <div v-if="!$v.order.shipping_address.maxLength">
                <small style="color: red">Shipping Address cannot be greater than 255 characters in length!</small>
              </div>
            </div>
            <div class="col form-group">
              <label for="billing_address">Billing Address</label>
              <div :style="{ border: billingAddressBorder }">
                <vue-ckeditor id="billing_address"
                              v-model="order.billing_address"
                              :config="config"
                              @blur="onBlur($event)"
                              @focus="onFocus($event)" />
              </div>
              <div v-if="!$v.order.billing_address.required">
                <small style="color: red">Billing Address required!</small>
              </div>
              <div v-if="!$v.order.billing_address.maxLength">
                <small style="color: red">Billing Address cannot be greater than 255 characters in length!</small>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div :style="{ border: itemsBorder}">
              <table class="table">
                <tr>
                  <th></th>
                  <th>Item</th>
                  <th>Price</th>
                  <th>Quantity</th>
                  <th>MSDS?</th>
                  <th>CofA?</th>
                </tr>
                <tr v-if="order.items" v-for="orderItem in order.items" :key="orderItem.id">
                  <td><button type="button" class="btn btn-outline-secondary" @click="onRemoveItem(orderItem.id)">-</button></td>
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
                        <input type="number" class="form-control" v-model.number="orderItem.quantity" disabled>
                      </div>
                      <div class="col-sm-1">
                        <input type="button" class="btn btn-outline-secondary btn-sm" value="-" :disabled="orderItem.quantity <= 1" @click="onUpdateItemQuantity(orderItem.id, -1)">
                      </div>
                      <div class="col-sm-1">
                        <input type="button" class="btn btn-outline-secondary btn-sm" value="+" @click="onUpdateItemQuantity(orderItem.id, 1)">
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-check">
                      <input :disabled="!orderItem.product.msds_pdf" :checked="orderItem.product.msds_pdf && orderItem.includes_msds === 1" class="form-check-input" type="checkbox" v-model="orderItem.includes_msds" true-value="1" false-value="0">
                      <a v-if="orderItem.product.msds_pdf && orderItem.includes_msds === 1" :href="'product_file/pdf/' + orderItem.product.id" target="_blank"><button type="button" class="btn btn-outline-primary">Download PDF</button></a>
                    </div>
                  </td>
                  <td>
                    <div class="form-check">
                      <input :disabled="!orderItem.cofa_pdf" :checked="orderItem.cofa_pdf && orderItem.includes_cofa === 1" class="form-check-input" type="checkbox" v-model="orderItem.includes_cofa" true-value="1" false-value="0">
                      <a v-if="orderItem.cofa_pdf && orderItem.includes_cofa === 1" :href="'item_file/pdf/' + orderItem.id" target="_blank"><button type="button" class="btn btn-outline-primary">Download PDF</button></a>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td><button type="button" class="btn btn-outline-secondary" @click="onAddItem()" >+</button></td>
                  <td>
                    <select class="form-control" v-model.number="item_id" @change="onChangeItem()">
                      <option selected value="0">Select an item</option>
                      <option v-for="entityItem in itemsInCurrentStock" :value="entityItem.id" v-html="entityItem.product.family.compound_id + ' ' + entityItem.product.family.compound + ' ' + entityItem.product.type + ' ' + entityItem.product.amount + ' ' + entityItem.product.amount_units + ' ' + entityItem.lot_number">
                      </option>
                    </select>
                  </td>
                  <td><input :disabled="!itemSelected" type="text" class="form-control" v-model="item.price"></td>
                  <td><input :disabled="!itemSelected" type="number" class="form-control" v-model.number="item.quantity"></td>
                  <td>
                    <div class="form-check">
                      <input :disabled="!(itemSelected && item.product.msds_pdf)" class="form-check-input" type="checkbox" v-model="item.includes_msds" true-value="1" false-value="0">
                    </div>
                  </td>
                  <td>
                    <div class="form-check">
                      <input :disabled="!(itemSelected && item.cofa)" class="form-check-input" type="checkbox" v-model="item.includes_cofa" true-value="1" false-value="0">
                    </div>
                  </td>
                </tr>
              </table>
            </div>
            <div v-if="!itemsValid">
              <small style="color: red">Items required!</small>
            </div>
          </div>
          <div class="row">
            <div class="col form-group">
              <label for="shipping_cost">Shipping Cost</label>
              <input type="text"
                    class="form-control"
                    :class="{ 'is-invalid': $v.order.shipping_cost.$invalid && !$v.order.shipping_cost.$pending,
                              'is-valid': !$v.order.shipping_cost.$invalid && !$v.order.shipping_cost.$pending }"
                    id="shipping_cost"
                    v-model="order.shipping_cost">
              <div v-if="!$v.order.shipping_cost.required" class="invalid-feedback">
                Shipping Cost is required!
              </div>
              <div v-if="!$v.order.shipping_cost.notZero" class="invalid-feedback">
                Shipping Cost cannot be zero!
              </div>
              <div v-if="!$v.order.shipping_cost.number" class="invalid-feedback">
                Shipping Cost must be a number!
              </div>
              <div v-if="!$v.order.shipping_cost.maxValue" class="invalid-feedback">
                Shipping Cost cannot be greater than 9999.99!
              </div>
            </div>
            <div class="col form-group">
              <label for="tax">Tax</label>
              <input type="text"
                    class="form-control"
                    :class="{ 'is-invalid': $v.order.tax.$invalid && !$v.order.tax.$pending,
                              'is-valid': !$v.order.tax.$invalid && !$v.order.tax.$pending }"
                    id="tax"
                    v-model="order.tax">
              <div v-if="!$v.order.tax.required" class="invalid-feedback">
                Tax is required!
              </div>
              <div v-if="!$v.order.tax.number" class="invalid-feedback">
                Tax must be a number!
              </div>
              <div v-if="!$v.order.tax.maxValue" class="invalid-feedback">
                Tax cannot be greater than 999.99!
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col form-group">
              <label for="sub_total">Sub Total</label>
              <input type="text" class="form-control" id="sub_total" v-model="subtotal" disabled>
            </div>
            <div class="col form-group">
              <label for="total">Total</label>
              <input type="text" class="form-control" id="total" v-model="total" disabled>
            </div>
          </div>
          <div class="row">
            <div class="col form-group">
              <label for="purchase_order_number">Purchase Order Number</label>
              <input type="text"
                    class="form-control"
                    :class="{ 'is-invalid': $v.order.purchase_order_number.$invalid && !$v.order.purchase_order_number.$pending,
                              'is-valid': !$v.order.purchase_order_number.$invalid && !$v.order.purchase_order_number.$pending }"
                    id="purchase_order_number"
                    v-model="order.purchase_order_number">
              <div v-if="!$v.order.purchase_order_number.maxValue" class="invalid-feedback">
                Purchase Order Number cannot be greater than 255 characters in length!
              </div>
            </div>
            <div class="col form-group">
              <label for="fulfilled_at">Fulfilled At</label>
              <input v-if="order.fulfilled_at" type="text" class="form-control" id="fulfilled_at" :value="new Date(order.fulfilled_at).toLocaleString()" disabled>
              <input v-else type="text" class="form-control" id="fulfilled_at" disabled>
            </div>
          </div>
          <div class="row">
            <div class="col form-group">
              <label for="notes">Notes</label>
              <div :style="{ border: notesBorder }">
                <vue-ckeditor id="notes"
                              v-model="order.notes"
                              :config="config"
                              @blur="onBlur($event)"
                              @focus="onFocus($event)" />
              </div>
              <div v-if="!$v.order.notes.maxLength">
                <small style="color: red">Notes cannot be greater than 65,535 characters in length!</small>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-outline-primary">Save</button>
          <button type="button" class="btn btn-outline-primary" @click="onFulfill(order)">Save and Fulfill</button>
          <button type="button" class="btn btn-outline-warning" @click="onCancel()">Cancel</button>
          <button v-if="order.fulfilled_at && order.fulfilled_at != null" type="button" class="btn btn-outline-secondary">Fetch Documents</button>
        </form>
      </div>
    </div>
    <div>
      <nav aria-label="Items Pagination Links" class="mt-4">
        <ul class="pagination">
          <li :class="[{disabled: !pagination[entity_name].prev_page_url}]" class="page-item">
            <a class="page-link" href="#" @click="fetchEntities(pagination[entity_name].prev_page_url)">Previous</a>
          </li>
          <li class="page-item disabled">
            <a class="page-link text-dark" href="#">Page {{ pagination[entity_name].current_page }} of {{ pagination[entity_name].last_page }}</a>
          </li>
          <li :class="[{disabled: !pagination[entity_name].next_page_url}]" class="page-item">
            <a class="page-link" href="#" @click="fetchEntities(pagination[entity_name].next_page_url)">Next</a>
          </li>
        </ul>
      </nav>
      <table class="table">
        <tr>
          <th>Date</th>
          <th>Order #</th>
          <th>Customer</th>
          <th>Contact</th>
          <th>Actions</th>
        </tr>
        <tr v-for="order in entities[entity_name]" :key="order.id">
          <td>{{ dateObject(order.created_at).toLocaleString() }}</td>
          <td>{{ order.contact_id.toString().padStart(2, '0') }}-{{ order.id.toString().padStart(4, '0') }}</td>
          <td>{{ order.contact.customer.name }} {{ order.coord2 }} {{ order.coord3 }}</td>
          <td>{{ order.contact.prefix }} {{ order.contact.first_name }} {{ order.contact.last_name }}</td>
          <td><div v-if="!showCreate"><button @click="populateForm(order)" class="btn btn-outline-secondary">Edit</button> <button @click="onDelete(order.id)" class="btn btn-outline-danger">Delete</button></div></td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
  import { Entity } from './Entity.js';
  import VueCkeditor from 'vue-ckeditor2';
  import { required, minLength, maxLength, minValue, maxValue, alphaNum, or, decimal, numeric } from 'vuelidate/lib/validators';
  export default {
    props: ['options'],
    mixins: [Entity],
    components: { VueCkeditor },
    data() {
      return {
        entity_name: 'Order',
        family_id: 0,
        item_id: 0,
        endpoint_url: 'api/order',
        edit: false,
        showCreate: false,
        formTitle: "Create",
        itemsValid: false,
        includeMSDS: false,
        includeCOFA: false,
        optionArray: this.options,
        config: {
          toolbar: [
            ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript']
          ],
          height: 130
        }
      }
    },

    validations: {
      order: {
        contact_id: {
          required,
          minValue: minValue(1)
        },
        shipping_address: {
          required,
          maxLength: maxLength(255)
        },
        billing_address: {
          required,
          maxLength: maxLength(255)
        },
        shipping_cost: {
          required,
          notZero(value) {
            return value != 0;
          },
          number: or(decimal, numeric),
          maxValue: maxValue(9999.99)
        },
        tax: {
          required,
          number: or(decimal, numeric),
          maxValue: maxValue(999.99)
        },
        purchase_order_number: {
          maxLength: maxLength(255)
        },
        notes: {
          maxLength: maxLength(65535)
        }
      }
    },

    created() {
      this.fetchEntities(`${this.endpoint_url}?filter=${this.optionArray['filter']}&orderedBy=${this.optionArray['ordered_by']}&paginate=${this.optionArray['paginate']}`);
      this.fetchEntities(this.endpoint_url, 'Item');
      this.fetchEntity('api/product_inventory_item/blank').then(res => {
        this.item = res.data;
        this.item.includes_msds = 0;
        this.item.includes_cofa = 0;
      });
      this.fetchEntities('api/contact', 'Contact');
      this.fetchEntity(`${this.endpoint_url}/blank`).then(res => {
        this.order = res.data;
        Vue.set(this.order, 'items', []);
      });
    },

    methods: {

      checkForm() {
        return !this.$v.order.$invalid && this.itemsValid;
      },

      toggleForm(title) {
        this.formTitle = title || "Create";
        this.showCreate = !this.showCreate;
      },

      populateForm(order) {
        this.order = order;
        this.reconcileItems();
        this.edit = true;
        this.itemsValid = true;
        this.toggleForm("Edit");
      },

      onCancel() {
        this.edit = false;
        this.itemsValid = false;
        if(this.order.id > 0) {
          for(let i in this.entities['Order']) {
            if(this.entities['Order'][i].id == this.order.id) {
              this.fetchEntity(`${this.endpoint_url}/${this.order.id}`).then(res => {
                this.entities['Order'][i] = res.data;
                this.fetchEntity(`${this.endpoint_url}/blank`).then(res => {
                  this.order = res.data;
                  Vue.set(this.order, 'items', []);
                });
              });
              break;
            }
          }
        } else {
          this.fetchEntity(`${this.endpoint_url}/blank`).then(res => {
            this.order = res.data;
            Vue.set(this.order, 'items', []);
          });
        }
        this.toggleForm();
        this.fetchEntities(this.endpoint_url, 'Item');
        this.fetchEntity('api/product_inventory_item/blank').then(res => {
          this.item = res.data;
          this.item.includes_msds = 0;
          this.item.includes_cofa = 0;
        });
        this.family_id = 0;
      },

      onChangeItem() {
        if(this.item_id > 0) {
          for(let i in this.entities['Item']) {
            if(this.entities['Item'][i].id == this.item_id) {
              this.item = this.entities['Item'][i];
              this.item.price = this.item.product.list_price;
              this.item.quantity = 1;
              this.item.includes_msds = 0;
              this.item.includes_cofa = 0;
              if(this.item.cofa_pdf) {
                this.includeCOFA = true;
              }
              if(this.item.product.msds) {
                this.includeMSDS = true;
              }
              break;
            }
          }
        } else {
          this.fetchEntity('api/product_inventory_item/blank').then(res => {
            this.item = res.data;
            this.item.includes_msds = 0;
            this.item.includes_cofa = 0;
          });
        }
      },

      onUpdateItemQuantity(id, change) {
        for(let i in this.order.items) {
          if(this.order.items[i].id == id) {
            let newQuant = this.order.items[i].quantity + change;
            Vue.set(this.order.items[i], 'quantity', newQuant);
            Vue.set(this.order.items, i, this.order.items[i]);
            break;
          }
        }
      },

      reconcileItems() {
        for(let i in this.entities['Item']) {
          for(let j in this.order.items) {
            if(this.entities['Item'][i].id == this.order.items[j].id) {
              this.entities['Item'].splice(i, 1);
              break;
            }
          }
        }
      },

      onChangeContact() {
        if(this.order.contact_id != 0) {
          for(let i in this.entities['Contact']) {
            if(this.entities['Contact'][i].id == this.order.contact_id) {
              this.order.contact = this.entities['Contact'][i];
              if(this.order.contact.shipping_address) {
                this.order.shipping_address = this.order.contact.shipping_address;
              }
              if(this.order.contact.billing_address) {
                this.order.billing_address = this.order.contact.billing_address;
              }
              break;
            }
          }
        } else {
          this.fetchEntity('api/contact/blank').then(res => {
            this.order.contact = res.data;
          });
        }
      },

      onAddItem() {
        if(this.itemSelected) {
          if(this.item.price && this.item.quantity) {
            for(let i in this.entities['Item']) {
              if(this.entities['Item'][i].id == this.item.id) {
                this.entities['Item'].splice(i, 1);
                this.order.items.push(this.item);
                break;
              }
            }
            this.fetchEntity('api/product_inventory_item/blank').then(res => {
              this.item = res.data;
              this.item.includes_msds = 0;
              this.item.includes_cofa = 0;
            });
            this.itemsValid = (this.order.items.length > 0 ? true : false);
            this.item_id = 0;
          } else {
            this.showAlert('warning', 'Please get the Item a price and quantity before adding to Order!');
          }
        } else {
          this.showAlert('warning', 'Please select an Item to add to the Order before clicking the \"+\" button!');
        }
      },

      onRemoveItem(item_id) {
        for(let i in this.order.items) {
          if(this.order.items[i].id == item_id) {
            this.entities['Item'].push(this.order.items[i]);
            this.entities['Item'].sort((item1, item2) => {
              return item2.id - item1.id;
            });
            this.order.items.splice(i, 1);
            break;
          }
        }
        this.itemsValid = (this.order.items.length > 0 ? true : false);
      },

      onSubmit(order) {
        if(this.checkForm()) {
          this.storeEntity(order).then(res => {
            this.emptyForm();
            this.showAlert('success', (!this.edit ? `${this.entity_name} added!`: `${this.entity_name} updated!`));
          });
          this.fetchEntities(this.endpoint_url, 'Item');
        } else {
          this.showAlert('danger', "Invalid Form Submittion!");
        }
      },

      onFulfill(order) {
        if(this.checkForm()) {
          let now = new Date();
          order.fulfilled_at = now.getFullYear() + "-" + (now.getMonth() + 1) + "-" + now.getDate() + " " + now.getHours() + ":" + now.getMinutes() + ":" + now.getSeconds();
          this.storeEntity(order).then(res => {
            this.emptyForm();
            this.showAlert('success', (!this.edit ? `${this.entity_name} added!`: `${this.entity_name} updated (Fulfilled)!`));
          });
          this.fetchEntities(this.endpoint_url, 'Item');
        } else {
          this.showAlert('danger', "Invalid Form Submittion!");
        }
      },

      onDelete(id) {
        if(confirm('Are you sure? This cannot be undone.')) {
          this.deleteEntity(id).then(res => {
            for(let i in this.entities['Order']) {
              if(this.entities['Order'][i].id == res.data.id) {
                this.entities['Order'].splice(i, 1);
                this.showAlert('success', 'Order deleted!');
                break;
              }
            }
          })
          .catch(err => {
            console.log(err);
          });
        }
      },

      emptyForm() {
        this.fetchEntity(`${this.endpoint_url}/blank`).then(res => {
          this.order = res.data;
          Vue.set(this.order, 'items', []);
        });
        this.itemsValid = false;
        this.toggleForm();
        this.fetchEntities();
      },

      onBlur(editor) {
        console.log(editor);
      },
      onFocus(editor) {
        console.log(editor);
      }
    },

    computed: {
      subtotal() {
        var subtotal = 0;
        if(this.order && this.order.items) {
          for(let i in this.order.items) {
            subtotal += Number(this.order.items[i].price)*Number(this.order.items[i].quantity);
          }
          subtotal += Number(this.order.shipping_cost);
          return subtotal.toFixed(2);
        } else {
          return subtotal.toFixed(2);
        }
      },

      total() {
        var total = 0;
        if(this.order) {
          total = Number(this.subtotal) + Number(this.order.tax);
          return total.toFixed(2);
        } else {
          return total.toFixed(2);
        }
      },

      itemsInCurrentStock() {
        if(this.entities['Item'].length > 0) {
          return this.entities['Item'].filter(u => {
            return u.in_current_stock;
          });
        } else {
          return [];
        }
      },

      shippingAddressBorder() {
        if(this.$v.order.shipping_address.$invalid) {
          return "1px solid red";
        } else {
          return "1px solid #5cb85c";
        }
      },

      billingAddressBorder() {
        if(this.$v.order.billing_address.$invalid) {
          return "1px solid red";
        } else {
          return "1px solid #5cb85c";
        }
      },

      notesBorder() {
        if(this.$v.order.notes.$invalid) {
          return "1px solid red";
        } else {
          return "1px solid #5cb85c";
        }
      },

      itemsBorder() {
        if(this.itemsValid) {
          return "1px solid #5cb85c";
        } else {
          return "1px solid red";
        }
      },

      itemSelected() {
        return this.item.hasOwnProperty('id') && this.item.id > 0;
      }
    }
  };
</script>
