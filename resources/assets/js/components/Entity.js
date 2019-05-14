export const Entity = {
  data() {
    return {
      customer: {},
      contact: {},
      order: {},
      product_family: {},
      product: {},
      item: {},
      entities: {
        'Customer': [],
        'Contact': [],
        'Order': [],
        'Family': [],
        'Product': [],
        'Item': []
      },
      pagination: {
        'Customer': {},
        'Contact': {},
        'Order': {},
        'Family': {},
        'Product': {},
        'Item': {}
      },
      message: {
        time: 2,
        countDown: 0,
        type: '',
        message: ''
      }
    }
  },

  methods: {
    fetchEntities(page_url, entity_name, callback) {
      page_url = page_url || this.endpoint_url;
      entity_name = entity_name || this.entity_name;
      callback = (typeof callback === 'function' ? callback : () => {});
      fetch(page_url)
        .then(res => res.json())
        .then(res => {
          if(res.data.length > 0) {
            this.entities[entity_name] = res.data;
            this.pagination[entity_name] = this.makePagination(res.meta, res.links);
            callback();
          }
        })
        .catch(err => console.log(err));
    },

    makePagination(meta, links) {
      let pagination = {
        current_page: meta.current_page,
        last_page: meta.last_page,
        next_page_url: links.next,
        prev_page_url: links.prev
      };
      return pagination;
    },

    async fetchEntity(page_url) {
      page_url = page_url || this.endpoint_url;
      return await fetch(page_url)
        .then(res => res.json())
        .then(res => {
            if(res.data) {
              return res;
            } else {
              return { message: 'nope' };
            }
        })
        .catch(err => {
          console.log(err);
          return { message: err };
        });
    },

    async deleteEntity(id, page_url) {
      page_url = page_url || this.endpoint_url;
      return await fetch(page_url + `/${id}`, {
        method: 'delete'
      })
        .then(res => res.json())
        .then(data => {
          return data;
        })
        .catch(err => {
          console.log(err);
          return false;
        });
    },

    async storeEntity(entity, page_url, contains_file) {
      page_url = page_url || this.endpoint_url;
      contains_file = contains_file || false;
      let method = (!this.edit ? 'post' : 'put');
      if(!contains_file) {
        let body = JSON.stringify(entity);
        return await fetch(page_url, {
          method: method,
          body: body,
          headers: {
            'Content-Type': 'application/json'
          }
        })
          .then(res => res.json())
          .then(data => {
            return data;
          })
          .catch(err => {
            console.log(err + " Error with storeEntity(), using JSON");
          });
      } else {
        let body = new FormData();
        for(var key in entity) {
          if(entity.hasOwnProperty(key)) {
            body.append(key, entity[key]);
          }
        }
        if(this.edit) {
          body.append('_method', 'put');
        }
        return await fetch(page_url, {
          method: 'post',
          body: body
        })
          .then(res => res.json())
          .then(data => {
            return data;
          })
          .catch(err => {
            console.log(err + " Error with storeEntity(), using FormData");
          });
      }
    },

    countDownChanged(time) {
      this.message.countDown = time;
    },

    showAlert(type, message) {
      this.message.type = type;
      this.message.message = message;
      this.message.countDown = this.message.time;
    },

    dateObject(dateObject) {
      let spaceIndex = dateObject.date.indexOf(" ");
      let periodIndex = dateObject.date.indexOf(".")
      let date = dateObject.date.slice(0, spaceIndex);
      let time = dateObject.date.slice(spaceIndex+1, periodIndex);
      return new Date(date + "T" + time + "Z");
    },

    dateString(dateString) {
      let spaceIndex = dateString.indexOf(" ");
      let date = dateString.slice(0, spaceIndex);
      let time = dateString.slice(spaceIndex+1);
      return new Date(dateString);
    },

    getDateString(date) {
      let mm = date.getMonth() + 1;
      let dd = date.getDate();

      return [
        date.getFullYear(),
        (mm>9 ? '' : '0') + mm,
        (dd>9 ? '' : '0') + dd
      ].join('-');
    }
  }
}
