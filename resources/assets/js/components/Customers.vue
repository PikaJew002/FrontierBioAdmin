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
    <h1>Customer Management</h1>
    <div v-if="displayCustomer">
      <button v-if="!showCreateCustomer" @click="toggleFormCustomer()" class="btn btn-outline-primary">Create Customer</button>
      <div v-if="showCreateCustomer" class="card bg-light mt-3">
        <div class="card-header">{{ formTitle }} Customer</div>
        <div class="card-body">
          <form @submit.prevent="onSubmitCustomer(customer)">
            <div class="form-group">
              <label for="name">Customer Name</label>
              <input type="text"
                    class="form-control"
                    :class="{ 'is-invalid': $v.customer.name.$invalid && !$v.customer.name.$pending,
                              'is-valid': !$v.customer.name.$invalid && !$v.customer.name.$pending }"
                    id="name"
                    placeholder="Customer Name"
                    v-model="customer.name">
              <div v-if="!$v.customer.name.required" class="invalid-feedback">
                Customer Name required!
              </div>
              <div v-if="!$v.customer.name.maxLength" class="invalid-feedback">
                Customer Name cannot be greater than 255 characters in length!
              </div>
            </div>
            <div class="form-group">
              <label for="short_name">Short Name</label>
              <input type="text"
                    class="form-control"
                    :class="{ 'is-invalid': $v.customer.short_name.$invalid && !$v.customer.short_name.$pending,
                              'is-valid': !$v.customer.short_name.$invalid && !$v.customer.short_name.$pending }"
                    id="short_name"
                    placeholder="Short Name"
                    v-model="customer.short_name">
              <div v-if="!$v.customer.short_name.maxLength" class="invalid-feedback">
                Short Name cannot be greater than 255 characters in length!
              </div>
            </div>
            <div class="row">
              <div class="col-sm-2 form-group">
                <input type="text"
                      class="form-control"
                      :class="{ 'is-invalid': $v.customer.number.$invalid && !$v.customer.number.$pending,
                                'is-valid': !$v.customer.number.$invalid && !$v.customer.number.$pending }"
                      id="number"
                      placeholder="Customer #"
                      v-model="customer.number">
                <div v-if="!$v.customer.number.maxLength" class="invalid-feedback">
                  Customer Number cannot be greater than 5 characters in length!
                </div>
              </div>
              <div class="col-sm-2 form-group">
                <span v-if="customer.id"> - {{ customer.id.toString().padStart(4, '0') }}</span>
              </div>
            </div>
            <button type="submit" class="btn btn-outline-primary">Save</button>
            <button type="button" class="btn btn-outline-warning" @click="onCancel(entity_name)">Cancel</button>
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
            <th>Customer Name</th>
            <th>Short Name</th>
            <th>Number</th>
            <th>Actions</th>
          </tr>
          <tr v-for="customer in entities[entity_name]" v-bind:key="customer.id">
            <td>{{ customer.name }}</td>
            <td>{{ customer.short_name }}</td>
            <td>{{ customer.number }}-{{ customer.id.toString().padStart(4, '0') }}</td>
            <td>
              <div v-if="!showCreateCustomer">
                <button @click="showContacts(customer, customer.id)" class="btn btn-outline-secondary">View Contacts</button>
                <button @click="populateFormCustomer(customer)" class="btn btn-outline-secondary">Edit</button>
                <button @click="onDeleteCustomer(customer.id)" class="btn btn-outline-danger">Delete</button>
              </div>
            </td>
          </tr>
        </table>
      </div>
    </div>
    <div v-if="displayContact">
      <em>Contacts of: </em>
      <strong>{{ customer.name }}</strong>
      <hr>
      <button v-if="!showCreateContact" @click="showContacts()" class="btn btn-outline-secondary">Back</button>
      <button v-if="!showCreateContact" @click="toggleFormContact()" class="btn btn-outline-primary">Create Contact</button>
      <div v-if="showCreateContact" class="card bg-light mt-3">
        <div class="card-header">{{ formTitle }} Contact</div>
        <div class="card-body">
          <form @submit.prevent="onSubmitContact(contact)">
            <div class="row">
              <div class="col-sm-2 form-group">
                <label for="prefix">Prefix</label>
                <input type="text"
                      class="form-control"
                      :class="{ 'is-invalid': $v.contact.prefix.$invalid && !$v.contact.prefix.$pending,
                                'is-valid': !$v.contact.prefix.$invalid && !$v.contact.prefix.$pending }"
                      id="prefix"
                      placeholder="Mr./Mrs./Dr."
                      v-model="contact.prefix">
                <div v-if="!$v.contact.prefix.maxLength" class="invalid-feedback">
                  Prefix cannot be greater than {{ $v.contact.prefix.$params.maxLength.max }} characters in length!
                </div>
              </div>
              <div class="col-sm-2 form-group">
                <label for="first_name">First Name</label>
                <input type="text"
                      class="form-control"
                      :class="{ 'is-invalid': $v.contact.first_name.$invalid && !$v.contact.first_name.$pending,
                                'is-valid': !$v.contact.first_name.$invalid && !$v.contact.first_name.$pending }"
                      id="first_name"
                      placeholder="First Name"
                      v-model="contact.first_name">
                <div v-if="!$v.contact.first_name.required" class="invalid-feedback">
                  First Name is required!
                </div>
                <div v-if="!$v.contact.first_name.maxLength" class="invalid-feedback">
                  First Name cannot be greater than {{ $v.contact.first_name.$params.maxLength.max }} characters in length!
                </div>
              </div>
              <div class="col-sm-2 form-group">
                <label for="last_name">Last Name</label>
                <input type="text"
                      class="form-control"
                      :class="{ 'is-invalid': $v.contact.last_name.$invalid && !$v.contact.last_name.$pending,
                                'is-valid': !$v.contact.last_name.$invalid && !$v.contact.last_name.$pending }"
                      id="last_name"
                      placeholder="Last Name"
                      v-model="contact.last_name">
                <div v-if="!$v.contact.last_name.required" class="invalid-feedback">
                  Last Name is required!
                </div>
                <div v-if="!$v.contact.last_name.maxLength" class="invalid-feedback">
                  Last Name cannot be greater than {{ $v.contact.last_name.$params.maxLength.max }} characters in length!
                </div>
              </div>
              <div class="col-sm-2 form-group">
                <label for="phone">Phone Number</label>
                <input type="tel"
                      class="form-control"
                      :class="{ 'is-invalid': $v.contact.phone.$invalid && !$v.contact.phone.$pending,
                                'is-valid': !$v.contact.phone.$invalid && !$v.contact.phone.$pending }"
                      id="phone"
                      placeholder="Phone Number"
                      v-model="contact.phone">
                <div v-if="!$v.contact.phone.required" class="invalid-feedback">
                  Phone Number is required!
                </div>
                <div v-if="!$v.contact.phone.maxLength" class="invalid-feedback">
                  Phone Number cannot be greater than {{ $v.contact.phone.$params.maxLength.max }} characters in length!
                </div>
              </div>
              <div class="col-sm-2 form-group">
                <label for="email">Email</label>
                <input type="email"
                      class="form-control"
                      :class="{ 'is-invalid': $v.contact.email.$invalid && !$v.contact.email.$pending,
                                'is-valid': !$v.contact.email.$invalid && !$v.contact.email.$pending }"
                      id="email"
                      placeholder="Email"
                      v-model="contact.email">
                <div v-if="!$v.contact.email.required" class="invalid-feedback">
                  Email is required!
                </div>
                <div v-if="!$v.contact.email.maxLength" class="invalid-feedback">
                  Email cannot be greater than {{ $v.contact.email.$params.maxLength.max }} characters in length!
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col form-group">
                <label for="shipping_address">Shipping Address</label>
                <div :style="{ border: shippingAddressBorder }">
                  <vue-ckeditor id="shipping_address"
                                v-model="contact.shipping_address"
                                :config="config"
                                @blur="onBlur($event)"
                                @focus="onFocus($event)" />
                </div>
                <div v-if="!$v.contact.shipping_address.maxLength">
                  <small style="color: red">Shipping Address cannot be greater than {{ $v.contact.shipping_address.$params.maxLength.max }} characters in length!</small>
                </div>
              </div>
              <div class="col form-group">
                <label for="billing_address">Billing Address</label>
                <div :style="{ border: billingAddressBorder }">
                  <vue-ckeditor id="billing_address"
                                v-model="contact.billing_address"
                                :config="config"
                                @blur="onBlur($event)"
                                @focus="onFocus($event)" />
                </div>
                <div v-if="!$v.contact.billing_address.maxLength">
                  <small style="color: red">Billing Address cannot be greater than {{ $v.contact.billing_address.$params.maxLength.max }} characters in length!</small>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-outline-primary">Save</button>
            <button type="button" class="btn btn-outline-warning" @click="onCancel('Contact')">Cancel</button>
          </form>
        </div>
      </div>
      <div>
        <table class="table">
          <tr>
            <th>Contact Name</th>
            <th>Phone Number</th>
            <th>Email</th>
            <th>Shipping Address</th>
            <th>Billing Address</th>
            <th>Actions</th>
          </tr>
          <tr v-for="contact in entities['Contact']" v-bind:key="contact.id">
            <td>{{ contact.prefix }} {{ contact.first_name }} {{ contact.last_name }}</td>
            <td>{{ contact.phone }}</td>
            <td>{{ contact.email }}</td>
            <td><span v-html="contact.shipping_address"></span></td>
            <td><span v-html="contact.billing_address"></span></td>
            <td>
              <div v-if="!showCreateContact">
                <button @click="populateFormContact(contact)" class="btn btn-outline-secondary">Edit</button>
                <button @click="onDeleteContact(contact.id)" class="btn btn-outline-danger">Delete</button>
                <a :href="'orders?filter=contact_id:' + contact.id"><button type="button" class="btn btn-outline-secondary">Orders</button></a>
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
  import { required, minLength, maxLength, minValue, maxValue, alphaNum } from 'vuelidate/lib/validators';
  export default {
    props: ['options'],
    mixins: [Entity],
    components: { VueCkeditor },
    data() {
      return {
        entity_name: 'Customer',
        errors: [],
        family_id: 0,
        endpoint_url: 'api/customer',
        edit: false,
        displayCustomer: true,
        displayContact: false,
        showCreateCustomer: false,
        showCreateContact: false,
        formTitle: "Create",
        contactFormTitle: "Create",
        config: {
          toolbar: [
            ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript']
          ],
          height: 130
        }
      };
    },

    validations() {
      if(this.displayCustomer) {
        return {
          customer: {
            name: {
              required,
              maxLength: maxLength(255)
            },
            short_name: {
              maxLength: maxLength(255)
            },
            number: {
              maxLength: maxLength(5)
            }
          }
        }
      } else {
        return {
          contact: {
            customer_id: {
              required,
              minValue: minValue(1)
            },
            prefix: {
              maxLength: maxLength(25)
            },
            first_name: {
              required,
              maxLength: maxLength(100)
            },
            last_name: {
              required,
              maxLength: maxLength(100)
            },
            phone: {
              required,
              maxLength: maxLength(25)
            },
            email: {
              required,
              maxLength: maxLength(100)
            },
            shipping_address: {
              maxLength: maxLength(255)
            },
            billing_address: {
              maxLength: maxLength(255)
            }
          }
        }
      }
    },

    computed: {
      shippingAddressBorder() {
        if(this.$v.contact.shipping_address.$invalid) {
          return "1px solid red";
        } else {
          return "1px solid #5cb85c";
        }
      },

      billingAddressBorder() {
        if(this.$v.contact.billing_address.$invalid) {
          return "1px solid red";
        } else {
          return "1px solid #5cb85c";
        }
      }
    },

    created() {
      this.fetchEntities();
    },

    methods: {
      checkForm(entity_name) {
        entity_name = entity_name || this.entity_name;
        // If the form being submitted is the customer form
        if(this.$v.customer) {
          return !this.$v.customer.$invalid;
        } else { // If the form being submitted is the contact form
          return !this.$v.contact.$invalid;
        }
      },

      emptyForm(entity_name) {
        entity_name = entity_name || this.entity_name;
        if(entity_name == this.entity_name) {
          this.fetchEntity(`${this.endpoint_url}/blank`).then(res => {
            this.customer = res.data;
          });
          this.customer_id = 0;
          this.fetchEntities();
          this.toggleFormCustomer();
        } else {
          this.fetchEntity('api/contact/blank').then(res => {
            this.contact = res.data;
          });
          this.fetchEntities(`api/contact?filter=customer_id:${this.customer_id}`, 'Contact');
          this.toggleFormContact();
        }
      },

      toggleFormCustomer(title) {
        this.formTitle = title || "Create";
        this.showCreateCustomer = !this.showCreateCustomer;
      },

      toggleFormContact(title) {
        this.contact.customer_id = this.customer_id;
        this.formTitle = title || "Create";
        this.showCreateContact = !this.showCreateContact;
      },

      populateFormCustomer(customer) {
        this.customer = customer;
        this.edit = true;
        this.toggleFormCustomer("Edit");
      },

      populateFormContact(contact) {
        this.contact = contact;
        this.edit = true;
        this.toggleFormContact("Edit");
      },

      showContacts(customer, customer_id) {
        this.customer = customer || {};
        this.customer_id = customer_id || 0;
        if(this.customer_id != 0) {
          this.fetchEntities(`api/contact?filter=customer_id:${this.customer_id}`, 'Contact');
        } else {
          this.entities['Contact'] = [];
        }
        this.toggleDisplay();
      },

      toggleDisplay() {
        this.displayCustomer = !this.displayCustomer;
        this.displayContact = !this.displayContact;
      },

      onSubmitCustomer(customer) {
        if(this.checkForm()) {
          this.storeEntity(customer).then(res => {
            this.showAlert('success', (!this.edit ? `${this.entity_name} added!` : `${this.entity_name} updated!`));
            this.emptyForm();
          });
        }
      },

      onSubmitContact(contact) {
        if(this.checkForm('Contact')) {
          this.storeEntity(contact, 'api/contact').then(res => {
            this.showAlert('success', (!this.edit ? 'Contact added!' : 'Contact updated!'));
            this.emptyForm('Contact');
          });
        }
      },

      onDeleteCustomer(id) {
        if(confirm('Are you sure? This cannot be undone.')) {
          this.deleteEntity(id).then(res => {
            for(let i in this.entities['Customer']) {
              if(this.entities['Customer'][i].id == res.data.id) {
                this.entities['Customer'].splice(i, 1);
                this.showAlert('success', 'Customer removed.');
                break;
              }
            }
          });
        }
      },

      onDeleteContact(id) {
        if(confirm('Are you sure? This cannot be undone.')) {
          this.deleteEntity(id, 'api/contact').then(res => {
            for(let i in this.entities['Contact']) {
              if(this.entities['Contact'][i].id == res.data.id) {
                this.entities['Contact'].splice(i, 1);
                this.showAlert('success', 'Contact removed.');
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

      onBlur(editor) {
        console.log(editor);
      },
      onFocus(editor) {
        console.log(editor);
      }
    }
  };
</script>
