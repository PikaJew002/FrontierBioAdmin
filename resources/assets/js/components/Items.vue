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
    <h1>Product Inventory Management</h1>
    <button v-if="optionArray['from'] != ''" type="button" class="btn btn-outline-primary"><a :href="`${from[1]}?filter=${from[2]}:${from[3]}`">Go back to {{ from[0] }}</a></button>
    <button v-if="!showCreate" @click="toggleForm()" class="btn btn-outline-primary">Create Item</button>
    <div v-if="showCreate" class="card bg-light mt-3">
      <div class="card-header">{{ formTitle }} Item <button v-if="edit && !recert" @click="showRecertForm()" class="btn btn-outline-secondary">Recertify Item</button></div>
      <div class="card-body">
        <form @submit.prevent="onSubmit(item)">
          <div class="row">
            <div class="col-sm-6 form-group">
              <label for="family_id">Product Family</label>
              <select :disabled="recert"
                      class="form-control"
                      :class="{ 'is-invalid': $v.family_id.$invalid && !$v.family_id.$pending,
                                'is-valid': !$v.family_id.$invalid && !$v.family_id.$pending }"
                      id="family_id"
                      v-model.number="family_id"
                      @change="onFamilyChange()">
                <option :selected="family_id == 0" value="0">Select a Product Family</option>
                <option v-if="entities['Family'].count != 0"
                        v-for="family in entities['Family']"
                        :value="family.id"
                        v-html="family.compound_id + ' - ' + family.compound">
                </option>
              </select>
              <div v-if="!$v.family_id.required || !$v.family_id.minValue" class="invalid-feedback">
                Product Family selection required!
              </div>
            </div>
            <div class="col-sm-6 form-group">
              <label for="product_id">Product</label>
              <select :disabled="family_id == 0 || recert"
                      class="form-control"
                      :class="{ 'is-invalid': $v.item.product_id.$invalid && !$v.item.product_id.$pending,
                                'is-valid': !$v.item.product_id.$invalid && !$v.item.product_id.$pending }"
                      id="product_id"
                      v-model.number="item.product_id">
                <option selected value="0">Select a Product</option>
                <option v-for="product in entities['Product']" :value="product.id">
                  <span v-if="product.type == 'Liquid'">
                    ({{ product.type }}) {{ product.amount }} {{ product.amount_units }} {{ product.concentration }} {{ product.concentration_units }} in {{ product.solvent }}
                  </span>
                  <span v-else-if="product.type == 'Solid'">
                    ({{ product.type }}) {{ product.amount }} {{ product.amount_units }}
                  </span>
                </option>
              </select>
              <div v-if="!$v.item.product_id.required || !$v.item.product_id.minValue" class="invalid-feedback">
                Product selection required!
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-4 form-group">
              <label for="lot_number">Lot Number</label>
              <input type="text"
                    class="form-control"
                    :class="{ 'is-invalid': $v.item.lot_number.$invalid && !$v.item.lot_number.$pending,
                              'is-valid': !$v.item.lot_number.$invalid && !$v.item.lot_number.$pending }"
                    placeholder="Lot Number"
                    id="lot_number"
                    v-model.trim="item.lot_number">
              <div v-if="!$v.item.lot_number.required" class="invalid-feedback">
                Lot Number is required!
              </div>
              <div v-if="!$v.item.lot_number.alphaNum" class="invalid-feedback">
                Lot Number can only contain letters and numbers (alpha numeric)!
              </div>
              <div v-if="!$v.item.lot_number.minLength" class="invalid-feedback">
                Lot Number must be at least 8 characters in length!
              </div>
              <div v-if="!$v.item.lot_number.maxLength" class="invalid-feedback">
                Lot Number cannot be greater than 255 characters in length!
              </div>
              <div v-if="!$v.item.lot_number.isUnique" class="invalid-feedback">
                Lot Number must be unique!
              </div>
            </div>
            <div class="col-sm-4 form-group">
              <label for="prep_date">
                Prep Date
                <span class="small" v-if="recert">
                  <em> (prepation/analysis date is automatically set to current date for recertified items, change as needed)</em>
                </span>
              </label>
              <input type="date"
                    class="form-control"
                    :class="{ 'is-invalid': $v.item.prep_date.$invalid && !$v.item.prep_date.$pending,
                              'is-valid': !$v.item.prep_date.$invalid && !$v.item.prep_date.$pending }"
                    id="prep_date"
                    v-model="item.prep_date">
              <div v-if="!$v.item.prep_date.required" class="invalid-feedback">
                Prepation date required!
              </div>
            </div>
            <div class="col-sm-4 form-group">
              <label for="exp_date">Exp Date</label>
              <input type="date"
                    class="form-control"
                    :class="{ 'is-invalid': $v.item.exp_date.$invalid && !$v.item.exp_date.$pending,
                              'is-valid': !$v.item.exp_date.$invalid && !$v.item.exp_date.$pending }"
                    id="exp_date"
                    v-model="item.exp_date">
              <div v-if="!$v.item.exp_date.required" class="invalid-feedback">
                Experation date required!
              </div>
              <div v-if="!$v.item.exp_date.afterDate" class="invalid-feedback">
                Experation date must be after prepation date!
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-3 form-group">
              <label for="current_stock">
                Current Stock
                <span class="small" v-if="recert">
                  <em> (stock is decremented by 1 automatically for recertified items, decrement additionally as needed)</em>
                </span>
              </label>
              <input type="number"
                    class="form-control"
                    :class="{ 'is-invalid': $v.item.current_stock.$invalid && !$v.item.current_stock.$pending,
                              'is-valid': !$v.item.current_stock.$invalid && !$v.item.current_stock.$pending }"
                    id="current_stock"
                    v-model.number="item.current_stock">
              <div v-if="!$v.item.current_stock.maxValue">
                Current Stock must be less than 999,999,999!
              </div>
            </div>
            <div class="col-sm-3 form-group">
              <label for="coord1">Coordinate 1</label>
              <input type="text"
                    class="form-control"
                    :class="{ 'is-invalid': $v.item.coord1.$invalid && !$v.item.coord1.$pending,
                              'is-valid': !$v.item.coord1.$invalid && !$v.item.coord1.$pending }"
                    placeholder="Coordinate 1"
                    id="coord1"
                    v-model.trim="item.coord1">
              <div v-if="!$v.item.coord1.maxLength">
                Coordinate 1 cannot be greater than 255 characters in length!
              </div>
            </div>
            <div class="col-sm-3 form-group">
              <label for="coord1">Coordinate 2</label>
              <input type="text"
                    class="form-control"
                    :class="{ 'is-invalid': $v.item.coord2.$invalid && !$v.item.coord2.$pending,
                              'is-valid': !$v.item.coord2.$invalid && !$v.item.coord2.$pending }"
                    placeholder="Coordinate 2"
                    id="coord2"
                    v-model.trim="item.coord2">
              <div v-if="!$v.item.coord2.maxLength">
                Coordinate 2 cannot be greater than 255 characters in length!
              </div>
            </div>
            <div class="col-sm-3 form-group">
              <label for="coord1">Coordinate 3</label>
              <input type="text"
                    class="form-control"
                    :class="{ 'is-invalid': $v.item.coord3.$invalid && !$v.item.coord3.$pending,
                              'is-valid': !$v.item.coord3.$invalid && !$v.item.coord3.$pending }"
                    placeholder="Coordinate 3"
                    id="coord3"
                    v-model.trim="item.coord3">
              <div v-if="!$v.item.coord3.maxLength">
                Coordinate 3 cannot be greater than 255 characters in length!
              </div>
            </div>
          </div>
          <div v-if="recert" class="form-group">
            <label for="previous_lot_number">Previous Lot Number</label>
            <input type="text"
                  class="form-control"
                  placeholder="Prev Lot Number"
                  id="previous_lot_number"
                  v-model="item.previous_lot_number"
                  disabled>
          </div>
          <div class="row">
            <div class="col-sm-4 form-group">
              <label for="cofa_pdf">C of A (.pdf)</label>
              <input type="file"
                    class="form-control-file"
                    id="cofa_pdf"
                    ref="cofa_pdf"
                    @change="onCofaPdfChange()">
              <span v-if="showWarningCofaPdf"><small class="text-warning">Uploading a new file will delete the old one!</small></span>
              <a v-if="oldCofaPdf" :href="'item_file/pdf/' + item.id" target="_blank"><button type="button" class="btn btn-outline-primary">Download File</button></a>
            </div>
            <div class="col-sm-2">
              <div v-if="oldCofaPdf" class="form-check">
                <input type="checkbox" class="form-check-input" id="deleteCofaPdf" v-model="deleteCofaPdf">
                <label for="deleteCofaPdf" class="form-check-label">Delete C of A (.pdf)?</label>
              </div>
            </div>
            <div class="col-sm-4 form-group">
              <label for="cofa_pdf">C of A (.docx)</label>
              <input type="file"
                    class="form-control-file"
                    id="cofa_docx"
                    ref="cofa_docx"
                    @change="onCofaDocxChange()">
              <span v-if="showWarningCofaDocx"><small class="text-warning">Uploading a new file will delete the old one!</small></span>
              <a v-if="oldCofaDocx" :href="'item_file/docx/' + item.id" target="_blank"><button type="button" class="btn btn-outline-primary">Download File</button></a>
            </div>
            <div class="col-sm-2">
              <div v-if="oldCofaDocx" class="form-check">
                <input type="checkbox" class="form-check-input" id="deleteCofaDocx" v-model="deleteCofaDocx">
                <label for="deleteCofaDocx" class="form-check-label">Delete C of A (.docx)?</label>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-outline-primary">
            Save
            <span v-if="recert"> Recertified Item</span>
          </button>
          <button type="button" class="btn btn-outline-warning" @click="onCancel()">
            Cancel
            <span v-if="recert"> Recertification</span>
          </button>
        </form>
      </div>
    </div>
    <div v-if="!showCreate">
      <div class="form-group row mt-4">
        <div class="col-sm-4">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" v-model="showNotInCurrentStock" id="showNotInCurrentStock">
            <label class="form-check-label" for="showNotInCurrentStock">Show recertified Items</label>
          </div>
        </div>
      </div>
    </div>
    <nav aria-label="Items Pagination Links" class="mt-4">
      <ul class="pagination">
        <li v-bind:class="[{disabled: !pagination[entity_name].prev_page_url}]" class="page-item">
          <a class="page-link" href="#" @click="fetchEntities(pagination[entity_name].prev_page_url)">Previous</a>
        </li>
        <li class="page-item disabled">
          <a class="page-link text-dark" href="#">Page {{ pagination[entity_name].current_page }} of {{ pagination[entity_name].last_page }}</a>
        </li>
        <li v-bind:class="[{disabled: !pagination[entity_name].next_page_url}]" class="page-item">
          <a class="page-link" href="#" @click="fetchEntities(pagination[entity_name].next_page_url)">Next</a>
        </li>
      </ul>
    </nav>
    <table class="table">
      <tr>
        <th>Compound</th>
        <th>Type</th>
        <th>Lot Number</th>
        <th>Preperation Date</th>
        <th>Experation Date</th>
        <th>Current Stock</th>
        <th>Location</th>
        <th>Previous Lot Number</th>
        <th v-if="showNotInCurrentStock">Recertified?</th>
        <th>Actions</th>
      </tr>
      <tr v-for="item in itemsInCurrentStock" :key="item.id">
        <td><span v-html="item.product.family.compound"></span></td>
        <td>{{ item.product.type }} {{ item.product.amount }} {{ item.product.amount_units }}</td>
        <td>{{ item.lot_number }}</td>
        <td>{{ item.prep_date }}</td>
        <td>{{ item.exp_date }}</td>
        <td>{{ item.current_stock }}</td>
        <td>{{ item.coord1 }}<br>{{ item.coord2 }}<br>{{ item.coord3 }}</td>
        <td>{{ item.previous_lot_number }}</td>
        <td v-if="showNotInCurrentStock"><span v-if="item.in_current_stock == 0" style="color: red">Yes</span></td>
        <td><button :disabled="showCreate" @click="populateForm(item)" class="btn btn-outline-secondary">Edit</button> <button :disabled="showCreate" @click="onDelete(item.id)" class="btn btn-outline-danger">Delete</button></td>
      </tr>
    </table>
  </div>
</template>

<script>
  import { Entity } from './Entity.js';
  import Search from './Search.vue';
  import { required, minLength, maxLength, minValue, maxValue, alphaNum } from 'vuelidate/lib/validators';
  export default {
    props: ['options'],
    mixins: [Entity],
    components: { Search },
    data() {
      return {
        entity_name: 'Item',
        endpoint_url: 'api/product_inventory_item',
        old_item: {},
        family_id: 0,
        item_id: 0,
        edit: false,
        recert: false,
        showNotInCurrentStock: false,
        showCreate: false,
        deleteCofaPdf: false,
        deleteCofaDocx: false,
        oldCofaPdf: "",
        oldCofaDocx: "",
        formTitle: "Create",
        optionArray: this.options,
        search: {
          options: this.options,
          items: []
        }
      }
    },

    validations: {
      family_id: {
        required,
        minValue: minValue(1)
      },

      item: {
        product_id: {
          required,
          minValue: minValue(1)
        },
        prep_date: {
          required
        },
        exp_date: {
          required,
          afterDate(value) {
            return new Date(this.item.prep_date) < new Date(value);
          }
        },
        lot_number: {
          required,
          alphaNum,
          minLength: minLength(8),
          maxLength: maxLength(225),
          async isUnique(value) {
            if(value === '' || !value) {
              return true;
            }
            return await fetch(`${this.endpoint_url}/check/${value}`)
            .then(res => {
              if(res.status !== 200) {
                // Item not found, lot number available
                return true;
              } else {
                return res.json().then(res => {
                  // Item found, lot number taken
                  // But lot number is taken by the same Item being edited
                  if(this.edit && res.data.id == this.item.id) {
                    return true;
                  // Lot number is taken and it's not by the Item being editted
                  } else {
                    return false;
                  }
                });
              }
            })
            .catch(error => {
              //Item not found, lot number available
              console.log(error);
              return false;
            });
          }
        },
        current_stock: {
          required,
          minValue: minValue(1),
          maxValue: maxValue(999999999)
        },
        coord1: {
          maxLength: maxLength(255)
        },
        coord2: {
          maxLength: maxLength(255)
        },
        coord3: {
          maxLength: maxLength(255)
        }
      }
    },

    computed: {
      itemsInCurrentStock() {
        if(this.entities['Item'].length > 0) {
          if(!this.showNotInCurrentStock) {
            return this.entities['Item'].filter(u => {
              return u.in_current_stock;
            });
          } else {
            return this.entities['Item'];
          }
        } else {
          return [];
        }
      },

      itemAttributes() {
        if(Object.keys(this.item).length > 0) {
          var attributes = [];
          var keys = Object.keys(this.item)
          for(let key in keys) {
            //if(key != 'id' && key != 'product_id' && key != 'created_at' && key != 'updated_at') {
            console.log(keys[key]);
            attributes.push(keys[key]);
            //}
          }
          return attributes;
        } else {
          return [];
        }
      },

      optionsURL() {
        return `?filter=${this.optionArray['filter']}&orderedBy=${this.optionArray['ordered_by']}&paginate=${this.optionArray['paginate']}`;
      },

      from() {
        if(this.optionArray['from'] != '') {
          return this.optionArray['from'].split(":");
        } else {
          return [];
        }
      },

      showWarningCofaPdf() {
        return this.oldCofaPdf;
      },

      showWarningCofaDocx() {
        return this.oldCofaDocx;
      }
    },

    created() {
      this.fetchEntities(`${this.endpoint_url}?filter=${this.optionArray['filter']}&orderedBy=${this.optionArray['ordered_by']}&paginate=${this.optionArray['paginate']}`);
      this.fetchEntity(`${this.endpoint_url}/blank`).then(res => {
        this.item = res.data;
      });
      this.fetchEntities('api/product', 'Product');
      this.fetchEntities('api/product_family', 'Family');
    },

    methods: {
      checkForm() {
        return (!this.$v.item.$invalid && !this.$v.family_id.$invalid);
      },

      toggleForm(title) {
        this.formTitle = title || "Create";
        this.showCreate = !this.showCreate;
      },

      showRecertForm() {
        this.recert = true;
        this.edit = false;
        this.formTitle = "Recertify";
        let today = new Date();
        this.item_id = this.item.id;
        this.item.id = 0;
        this.item.previous_lot_number = this.item.lot_number;
        this.item.lot_number = "";
        this.item.prep_date = this.getDateString(new Date());
        this.item.exp_date = "";
        this.item.current_stock -= 1;
      },

      populateForm(item) {
        this.item = item;
        this.edit = true;
        this.family_id = this.item.product.family_id;
        for(let i in this.entities['Family']) {
          if(this.entities['Family'][i].id == this.family_id) {
            this.entities['Product'] = this.entities['Family'][i].products;
            break;
          }
        }
        if(this.item.cofa_pdf) {
          this.deleteCofaPdf = false;
          this.oldCofaPdf = item.cofa_pdf;
        }
        if(this.item.cofa_docx) {
          this.deleteCofaDocx = false;
          this.oldCofaDocx = item.cofa_docx;
        }
        this.toggleForm("Edit");
      },

      onCancel() {
        if(this.recert) {
          this.recert = false;
          this.fetchEntities();
          this.fetchEntity(`${this.endpoint_url}/${this.item_id}`).then(res => {
            this.item = res.data;
            this.item_id = 0;
          });
        } else {
          this.edit = false;
          this.emptyForm();
        }
      },

      onFamilyChange() {
        this.item.product_id = 0;
        for(let i in this.entities['Family']) {
          if(this.entities['Family'][i].id == this.family_id) {
            this.entities['Product'] = this.entities['Family'][i].products;
            break;
          }
        }
      },

      emptyForm() {
        this.fetchEntity(`${this.endpoint_url}/blank`).then(res => {
          this.item = res.data;
        });
        this.deleteCofaPdf = false;
        this.deleteCofaDocx = false;
        this.oldCofaPdf = "";
        this.oldCofaDocx = "";
        this.family_id = 0;
        this.toggleForm();
        this.fetchEntities();
      },

      onSubmit(item) {
        if(this.checkForm()) {
          if(this.deleteCofaPdf) {
            item.cofa_pdf = "delete";
          }
          if(this.deleteCofaDocx) {
            item.cofa_docx = "delete";
          }
          if(this.recert) {
            var a = this;
            this.fetchEntity(`${this.endpoint_url}/${this.item_id}`).then(res => {
              a.old_item = res.data;
              a.old_item.in_current_stock = 0;
              let hasFile = (item.cofa_pdf || item.cofa_docx ? true : false);
              this.storeEntity(item, this.endpoint_url, this.entity_name, hasFile).then(res => {
                this.edit = true;
                this.recert = false;
                this.storeEntity(this.old_item).then(res => {
                  this.showAlert('success', `New recertified ${this.entity_name} added. Previous ${this.entity_name} has been removed from current stock.`);
                  this.emptyForm();
                });
              });
            });
            this.item_id = 0;
          } else {
            let hasFile = (item.cofa_pdf || item.cofa_docx ? true : false);
            this.storeEntity(item, this.endpoint_url, this.entity_name, hasFile).then(res => {
              this.showAlert('success', (!this.edit ? `${this.entity_name} added!`: `${this.entity_name} updated!`));
              this.emptyForm();
            });
          }
        } else {
          this.showAlert('danger', "Invalid Form Submittion!");
        }
      },

      onDelete(id) {
        if(confirm('Are you sure? This cannot be undone.')) {
          this.deleteEntity(id).then(res => {
            this.showAlert('success', `${this.entity_name} removed.`);
            this.fetchEntities();
          });
        }
      },

      onCofaPdfChange() {
        this.item.cofa_pdf = this.$refs.cofa_pdf.files[0];
      },

      onCofaDocxChange() {
        this.item.cofa_docx = this.$refs.cofa_docx.files[0];
      }

    }
  };
</script>
