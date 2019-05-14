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
    <h1>Product Management</h1>
    <div v-if="displayFamily">
      <button v-if="!showCreateFamily" @click="toggleFormFamily()" class="btn btn-outline-primary">Create Product Family</button>
      <div v-if="showCreateFamily" class="card bg-light mt-3">
        <div class="card-header">{{ formTitle }} Product Family</div>
        <div class="card-body">
          <form @submit.prevent="onSubmitFamily()">
            <div class="form-group">
              <label for="compound_id">Compound Identifier</label>
              <input type="text"
                    class="form-control"
                    :class="{ 'is-invalid': $v.product_family.compound_id.$invalid && !$v.product_family.compound_id.$pending,
                              'is-valid': !$v.product_family.compound_id.$invalid && !$v.product_family.compound_id.$pending }"
                    id="compound_id"
                    placeholder="Compound Identifier"
                    v-model.trim="product_family.compound_id">
              <div v-if="!$v.product_family.compound_id.required" class="invalid-feedback">
                Compound Identifier required!
              </div>
              <div v-if="!$v.product_family.compound_id.maxLength" class="invalid-feedback">
                Compound Identifier cannot be greater than 255 characters in length!
              </div>
            </div>
            <div class="form-group">
              <label for="compound">Compound Name</label>
              <div :style="{ border: border }">
                <vue-ckeditor id="compound"
                              v-model="product_family.compound"
                              :config="config"
                              @blur="onBlur($event)"
                              @focus="onFocus($event)" />
              </div>
              <div v-if="!$v.product_family.compound.required">
                <small style="color: red">Compound required!</small>
              </div>
              <div v-if="!$v.product_family.compound.maxLength">
                <small style="color: red">Compound cannot be greater than 255 characters in length!</small>
              </div>
            </div>
            <div class="form-group">
              <label for="short_name">Short Name</label>
              <input type="text"
                    class="form-control"
                    :class="{ 'is-invalid': $v.product_family.short_name.$invalid && !$v.product_family.short_name.$pending,
                              'is-valid': !$v.product_family.short_name.$invalid && !$v.product_family.short_name.$pending }"
                    id="short_name"
                    placeholder="Short Name"
                    v-model.trim="product_family.short_name">
              <div v-if="!$v.product_family.short_name.maxLength" class="invalid-feedback">
                Short Name cannot be greater than 255 characters in length!
              </div>
            </div>
            <button type="submit" class="btn btn-outline-primary">Save</button>
            <button type="button" class="btn btn-outline-warning" @click="onCancel('Family')">Cancel</button>
          </form>
        </div>
      </div>
      <div>
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
            <th>Compound ID</th>
            <th>Compound Name</th>
            <th>Short Name</th>
            <th>Actions</th>
          </tr>
          <tr v-for="product_family in entities[entity_name]" v-bind:key="product_family.id">
            <td>{{ product_family.compound_id }}</td>
            <td v-html="product_family.compound"></td>
            <td>{{ product_family.short_name }}</td>
            <td>
              <div v-if="!showCreateFamily">
                <button @click="showProducts(product_family, product_family.id)" class="btn btn-outline-secondary">View Products</button>
                <button @click="populateFormFamily(product_family)" class="btn btn-outline-secondary">Edit</button>
                <button @click="onDeleteFamily(product_family.id)" class="btn btn-outline-danger">Delete</button>
              </div>
            </td>
          </tr>
        </table>
      </div>
    </div>
    <div v-if="displayProduct">
      <em>Products containing:</em>
      <strong><span v-html="product_family.compound"></span></strong>
      <hr>
      <button v-if="!showCreateProduct" @click="showProducts()" class="btn btn-outline-secondary">Back</button>
      <button v-if="!showCreateProduct" @click="toggleFormProduct()" class="btn btn-outline-primary">Create Product</button>
      <div v-if="showCreateProduct" class="card bg-light mt-3">
        <div class="card-header">{{ formTitle }} Product</div>
        <div class="card-body">
          <form @submit.prevent="onSubmitProduct(product)">
            <div class="form-group">
              <label for="type">Type</label>
              <div class="row">
                <div class="col-sm-2">
                  <select id="type"
                          class="form-control"
                          :class="{ 'is-invalid': $v.product.type.$invalid && !$v.product.type.$pending,
                                    'is-valid': !$v.product.type.$invalid && !$v.product.type.$pending }"
                          v-model="product.type"
                          v-on:change="onTypeChange()">
                    <option selected value="">Select a type</option>
                    <option value="Liquid">Liquid</option>
                    <option value="Solid">Solid</option>
                  </select>
                  <div v-if="!$v.product.type.required" class="invalid-feedback">
                    Type is required!
                  </div>
                  <div v-if="!$v.product.type.isValidType" class="invalid-feedback">
                    Type must be Liquid or Solid!
                  </div>
                </div>
              </div>
            </div>
            <div v-if="product.type == 'Liquid'" class="form-group">
              <label for="concentration">Concentration</label>
              <div class="row">
                <div class="col-sm-2">
                  <input type="text"
                        class="form-control"
                        :class="{ 'is-invalid': $v.product.concentration.$invalid && !$v.product.concentration.$pending,
                                  'is-valid': !$v.product.concentration.$invalid && !$v.product.concentration.$pending }"
                        id="concentration"
                        placeholder="Concentration"
                        v-model.number="product.concentration">
                  <div v-if="!$v.product.concentration.required" class="invalid-feedback">
                    Concentration is required!
                  </div>
                  <div v-if="!$v.product.concentration.notZero" class="invalid-feedback">
                    Concentration cannot be zero!
                  </div>
                  <div v-if="!$v.product.concentration.number" class="invalid-feedback">
                    Concentration must be a number!
                  </div>
                </div>
                <div class="col-sm-2">
                  <input type="text"
                        class="form-control"
                        :class="{ 'is-invalid': $v.product.concentration_units.$invalid && !$v.product.concentration_units.$pending,
                                  'is-valid': !$v.product.concentration_units.$invalid && !$v.product.concentration_units.$pending }"
                        placeholder="Conc. Units"
                        v-model="product.concentration_units">
                  <div v-if="!$v.product.concentration_units.required" class="invalid-feedback">
                    Concentration Units are required!
                  </div>
                  <div v-if="!$v.product.concentration_units.maxLength" class="invalid-feedback">
                    Concentration Units cannot be greater than 10 characters in length!
                  </div>
                </div>
              </div>
            </div>
            <div v-if="product.type == 'Liquid'" class="form-group">
              <label for="solvent">Solvent</label>
              <div class="row">
                <div class="col-sm-2">
                  <input type="text"
                        class="form-control"
                        :class="{ 'is-invalid': $v.product.solvent.$invalid && !$v.product.solvent.$pending,
                                  'is-valid': !$v.product.solvent.$invalid && !$v.product.solvent.$pending }"
                        id="solvent"
                        placeholder="Solvent"
                        v-model="product.solvent">
                  <div v-if="!$v.product.solvent.required" class="invalid-feedback">
                    Solvent is required!
                  </div>
                  <div v-if="!$v.product.solvent.maxLength" class="invalid-feedback">
                    Solvent cannot be greater than 255 characters in length!
                  </div>
                </div>
              </div>
            </div>
            <div v-if="product.type != ''" class="form-group">
              <label for="amount">
                Amount
                <span v-if="product.type == 'Liquid'"> (volume)</span>
                <span v-else> (mass)</span>
              </label>
              <div class="row">
                <div class="col-sm-2">
                  <input type="text"
                        class="form-control"
                        :class="{ 'is-invalid': $v.product.amount.$invalid && !$v.product.amount.$pending,
                                  'is-valid': !$v.product.amount.$invalid && !$v.product.amount.$pending }"
                        id="amount"
                        placeholder="Amount"
                        v-model="product.amount">
                  <div v-if="!$v.product.amount.required" class="invalid-feedback">
                    Amount is required!
                  </div>
                  <div v-if="!$v.product.amount.notZero" class="invalid-feedback">
                    Amount cannot be zero!
                  </div>
                  <div v-if="!$v.product.amount.number" class="invalid-feedback">
                    Amount must be a number!
                  </div>
                </div>
                <div class="col-sm-2">
                  <input type="text"
                        class="form-control"
                        :class="{ 'is-invalid': $v.product.amount_units.$invalid && !$v.product.amount_units.$pending,
                                  'is-valid': !$v.product.amount_units.$invalid && !$v.product.amount_units.$pending }"
                        placeholder="Amount Units"
                        v-model="product.amount_units">
                  <div v-if="!$v.product.amount_units.required" class="invalid-feedback">
                    Amount Units are required!
                  </div>
                  <div v-if="!$v.product.amount_units.maxLength" class="invalid-feedback">
                    Amount Units cannot be greater than 10 characters in length!
                  </div>
                </div>
              </div>
            </div>
            <div v-if="product.type != ''" class="form-group">
              <label for="list_price">List Price</label>
              <div class="row">
                <div class="col-sm-2">
                  <input type="text"
                        class="form-control"
                        :class="{ 'is-invalid': $v.product.list_price.$invalid && !$v.product.list_price.$pending,
                                  'is-valid': !$v.product.list_price.$invalid && !$v.product.list_price.$pending }"
                        id="list_price"
                        placeholder="List Price"
                        v-model.number="product.list_price">
                  <div v-if="!$v.product.list_price.number" class="invalid-feedback">
                    List Price must be a number!
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-4 form-group">
                <label for="msds_pdf">MSDS (.pdf)</label>
                <input type="file"
                      class="form-control-file"
                      id="msds_pdf"
                      ref="msds_pdf"
                      @change="onMsdsPdfChange()">
                <span v-if="showWarningMsdsPdf"><small class="text-warning">Uploading a new file will delete the old one!</small></span>
                <a v-if="product.msds_pdf" :href="'product_file/pdf/' + product.id" target="_blank"><button type="button" class="btn btn-outline-primary">Download File</button></a>
              </div>
              <div class="col-sm-2">
                <div v-if="oldMsdsPdf" class="form-check">
                  <input type="checkbox" class="form-check-input" id="deleteMsdsPdf" v-model="deleteMsdsPdf">
                  <label for="deleteMsdsPdf" class="form-check-label">Delete MSDS (.pdf)?</label>
                </div>
              </div>
              <div class="col-sm-4 form-group">
                <label for="msds_docx">MSDS (.docx)</label>
                <input type="file"
                      class="form-control-file"
                      id="msds_docx"
                      ref="msds_docx"
                      @change="onMsdsDocxChange()">
                <span v-if="showWarningMsdsDocx"><small class="text-warning">Uploading a new file will delete the old one!</small></span>
                <a v-if="product.msds_docx" :href="'product_file/docx/' + product.id" target="_blank"><button type="button" class="btn btn-outline-primary">Download File</button></a>
              </div>
              <div class="col-sm-2">
                <div v-if="product.msds_docx" class="form-check">
                  <input type="checkbox" class="form-check-input" id="deleteMsdsDocx" v-model="deleteMsdsDocx">
                  <label for="deleteMsdsDocx" class="form-check-label">Delete MSDS (.docx)?</label>
                </div>
              </div>
            </div>
            <input type="hidden" v-model="product.family_id" name="item_id">
            <button type="submit" class="btn btn-outline-primary">Save</button>
            <button type="button" class="btn btn-outline-warning" @click="onCancel('Product')">Cancel</button>
          </form>
        </div>
      </div>
      <div>
        <table class="table">
          <tr>
            <th>Type</th>
            <th>Concentration</th>
            <th>Solvent</th>
            <th>Amount</th>
            <th>List Price</th>
            <th>MSDS (.pdf)</th>
            <th>MSDS (.docx)</th>
            <th>Actions</th>
          </tr>
          <tr v-for="product in entities['Product']" v-bind:key="product.id">
            <td>{{ product.type }}</td>
            <td>
              <span v-if="product.type == 'Liquid'">{{ product.concentration }} {{ product.concentration_units }}</span>
              <span v-else style="color: grey"><em>N/A</em></span>
            </td>
            <td>
              <span v-if="product.type == 'Liquid'">{{ product.solvent }}</span>
              <span v-else style="color: grey"><em>N/A</em></span>
            </td>
            <td>{{ product.amount }} {{ product.amount_units }}</td>
            <td>${{ product.list_price }}</td>
            <td>
              <span v-if="product.msds_pdf">Yes</span>
              <span style="color: red" v-else>No</span>
            </td>
            <td>
              <span v-if="product.msds_docx">Yes</span>
              <span style="color: red" v-else>No</span>
            </td>
            <td>
              <div v-if="!showCreateProduct">
                <button @click="populateFormProduct(product)" class="btn btn-outline-secondary">Edit</button>
                <button @click="onDeleteProduct(product.id)" class="btn btn-outline-danger">Delete</button>
              </div>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
  import { Entity } from './Entity.js';
  import VueCkeditor from 'vue-ckeditor2';
  import { required, requiredIf, minLength, maxLength, minValue, maxValue, numeric, alphaNum, decimal, or } from 'vuelidate/lib/validators';
  export default {
    props: ['options'],
    mixins: [Entity],
    components: { VueCkeditor },
    data() {
      return {
        entity_name: 'Family',
        family_id: 0,
        endpoint_url: 'api/product_family',
        edit: false,
        displayFamily: true,
        displayProduct: false,
        showCreateFamily: false,
        showCreateProduct: false,
        deleteMsdsPdf: false,
        deleteMsdsDocx: false,
        oldMsdsPdf: "",
        oldMsdsDocx: "",
        formTitle: "Create",
        config: {
          toolbar: [
            ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript']
          ],
          height: 60
        }
      };
    },

    validations() {
      if(this.displayFamily) {
        return {
          product_family: {
            compound_id: {
              required,
              maxLength: maxLength(255)
            },
            compound: {
              required,
              maxLength: maxLength(255)
            },
            short_name: {
              maxLength: maxLength(255)
            }
          }
        }
      } else {
        if(this.product.type == "Liquid") {
          return {
            product: {
              family_id: {
                required,
                minValue: minValue(1)
              },
              type: {
                required,
                isValidType(value) {
                  return (value == "Liquid" || value == "Solid");
                }
              },
              concentration: {
                required: requiredIf(function(value) {
                  return this.product.type == "Liquid";
                }),
                notZero(value) {
                  return value != 0;
                },
                number: or(decimal, numeric)
              },
              concentration_units: {
                required: requiredIf(function(value) {
                  return this.product.type == "Liquid";
                }),
                maxLength: maxLength(10)
              },
              solvent: {
                required: requiredIf(function(value) {
                  return this.product.type == "Liquid";
                }),
                maxLength: maxLength(255)
              },
              amount: {
                required,
                notZero(value) {
                  return value != 0;
                },
                number: or(decimal, numeric)
              },
              amount_units: {
                required,
                maxLength: maxLength(10)
              },
              list_price: {
                number: or(decimal, numeric)
              }
            }
          }
        } else {
          return {
            product: {
              family_id: {
                required,
                minValue: minValue(1)
              },
              type: {
                required,
                isValidType(value) {
                  return (value == "Liquid" || value == "Solid");
                }
              },
              amount: {
                required,
                notZero(value) {
                  return value != 0;
                },
                number: or(decimal, numeric)
              },
              amount_units: {
                required,
                maxLength: maxLength(10)
              },
              list_price: {
                number: or(decimal, numeric)
              }
            }
          }
        }
      }
    },

    computed: {
      border() {
        if(this.displayFamily && this.$v.product_family.compound.$invalid) {
          return "1px solid red";
        } else {
          return "1px solid #5cb85c";
        }
      },

      showWarningMsdsPdf() {
        return this.oldMsdsPdf;
      },

      showWarningMsdsDocx() {
        return this.oldMsdsDocx;
      }
    },

    created() {
      this.fetchEntities();
      this.fetchEntity(`${this.endpoint_url}/blank`).then(res => {
        this.product_family = res.data;
      });
      this.fetchEntity('api/product/blank').then(res => {
        this.product = res.data;
      });
    },

    methods: {

      checkForm(entity_name) {
        entity_name = entity_name || this.entity_name;
        // If the form being submitted is the product family form
        if(this.$v.product_family) {
          return !this.$v.product_family.$invalid;
        } else { // If the form being submitted is the product form
          return !this.$v.product.$invalid;
        }
      },

      emptyForm(entity_name) {
        entity_name = entity_name || this.entity_name;
        if(entity_name == this.entity_name) { // For ProductFamily
          this.fetchEntity(`${this.endpoint_url}/blank`).then(res => {
            this.product_family = res.data;
          });
          this.family_id = 0;
          this.fetchEntities();
          this.toggleFormFamily();
        } else { // For Product
          this.fetchEntity('api/product/blank').then(res => {
            this.product = res.data;
          });
          this.deleteMsdsPdf = false;
          this.deleteMsdsDocx = false;
          this.oldMsdsPdf = "";
          this.oldMsdsDocx = "";
          this.fetchEntities(`api/product?filter=family_id:${this.family_id}`, 'Product');
          this.toggleFormProduct();
        }
      },

      toggleFormFamily(title) {
        this.formTitle = title || "Create";
        this.showCreateFamily = !this.showCreateFamily;
      },

      toggleFormProduct(title) {
        this.product.family_id = this.family_id;
        this.formTitle = title || "Create";
        this.showCreateProduct = !this.showCreateProduct;
      },

      populateFormFamily(product_family) {
        this.product_family = product_family;
        this.edit = true;
        this.toggleFormFamily("Edit");
      },

      populateFormProduct(product) {
        this.product = product;
        this.edit = true;
        if(this.product.msds_pdf) {
          this.deleteMsdsPdf = false;
          this.oldMsdsPdf = product.msds_pdf;
        }
        if(this.product.msds_docx) {
          this.deleteMsdsDocx = false;
          this.oldMsdsDocx = product.msds_docx;
        }
        this.toggleFormProduct("Edit");
      },

      showProducts(family, family_id) {
        this.product_family = family || {};
        this.family_id = family_id || 0;
        if(this.family_id != 0) {
          this.entities['Product'] = this.product_family.products;
        } else {
          this.fetchEntity(`${this.endpoint_url}/describe`).then(res => {
            this.product_family = res.data;
          });
          this.entities['Product'] = [];
        }
        this.toggleDisplay();
      },

      toggleDisplay() {
        this.displayFamily = !this.displayFamily;
        this.displayProduct = !this.displayProduct;
      },

      onTypeChange() {
        this.product.concentration = 0;
        this.product.concentration_units = "";
        this.product.solvent = "";
        this.product.amount = 0;
        this.product.amount_units = "";
        this.product.list_price = 0;
      },

      onSubmitProduct(product) {
        if(this.checkForm('Product')) {
          if(this.deleteMsdsPdf) {
            product.msds_pdf = "delete";
          }
          if(this.deleteMsdsDocx) {
            product.msds_docx = "delete";
          }
          if(product.msds_pdf || product.msds_docx) {
            this.storeEntity(product, 'api/product', true).then(res => {
              this.showAlert('success', (!this.edit ? 'Product added!': 'Product updated!'));
              this.emptyForm('Product');
            });
          } else {
            this.storeEntity(product, 'api/product').then(res => {
              this.showAlert('success', (!this.edit ? 'Product added!': 'Product updated!'));
              this.emptyForm('Product');
            });;
          }
        } else {
          this.showAlert('danger', "Invalid Form Submittion!");
        }
      },

      onSubmitFamily() {
        if(this.checkForm()) {
          this.storeEntity(this.product_family).then(res => {
            this.showAlert('success', (!this.edit ? `${this.entity_name} added!` : `${this.entity_name} updated!`));
            this.emptyForm();
          });
        } else {
          this.showAlert('danger', "Invalid Form Submittion!");
        }
      },

      onDeleteFamily(id) {
        if(confirm('Are you sure? This cannot be undone.')) {
          this.deleteEntity(id).then(res => {
            for(let i in this.entities['Family']) {
              if(this.entities['Family'][i].id == res.data.id) {
                this.entities['Family'].splice(i, 1);
                this.showAlert('success', 'Product Family removed.');
                break;
              }
            }
          });
        }
      },

      onDeleteProduct(id) {
        if(confirm('Are you sure? This cannot be undone.')) {
          this.deleteEntity(id, 'api/product').then(res => {
            for(let i in this.entities['Product']) {
              if(this.entities['Product'][i].id == res.data.id) {
                this.entities['Product'].splice(i, 1);
                this.showAlert('success', 'Product removed.');
                break;
              }
            }
          });
        }
      },

      onCancel(entity_name) {
        this.edit = false;
        this.emptyForm(entity_name);
      },

      onMsdsPdfChange() {
        this.product.msds_pdf = this.$refs.msds_pdf.files[0];
      },

      onMsdsDocxChange() {
        this.product.msds_docx = this.$refs.msds_docx.files[0];
      },

      onBlur(editor) {
        console.log(editor);
      },
      onFocus(editor) {
        console.log(editor);
      }
    }
  };
</script>
