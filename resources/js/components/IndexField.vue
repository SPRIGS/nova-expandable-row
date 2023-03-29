<template>
  <span ref="indexField" class="text-center">
    <Teleport :to="toggleTeleportTarget" v-if="isMounted" :disabled="!this.field.moveToActions">
      <div
        class="rounded active:outline-none active:ring focus:outline-none focus:ring focus:ring-primary-200 dark:focus:ring-gray-600 -order-1 flex items-center justify-center"
        style="order: -1" @click.stop="toggleExpandedRow">
        <span v-if="!this.field.moveToActions" >{{this.field.toggleLabel}}</span>
        <button class="toolbar-button flex items-center cursor-pointer select-none">
          <div class="py-0.5 px-2 rounded">
            <svg :class="{ 'rotate-180': rowExpanded }" class=" relative opacity-0 flex flex-shrink-0 transform ml-auto"
              xmlns="http://www.w3.org/2000/svg" width="14" height="12" viewBox="0 0 10 6" to="">
              <path class="fill-current"
                d="M8.292893.292893c.390525-.390524 1.023689-.390524 1.414214 0 .390524.390525.390524 1.023689 0 1.414214l-4 4c-.390525.390524-1.023689.390524-1.414214 0l-4-4c-.390524-.390525-.390524-1.023689 0-1.414214.390525-.390524 1.023689-.390524 1.414214 0L5 3.585786 8.292893.292893z">
              </path>
            </svg>
          </div>
        </button>
      </div>
    </Teleport>
  </span>


  <Teleport :to="to" v-if="isMounted">
    <td :colspan="1" v-if="rowExpanded && resource"></td>
    <td :colspan="columnCount - 1" v-if="rowExpanded && resource">
      <div class="px-4 pb-4">
        <div class="bg-white dark:bg-gray-800 rounded-b shadow pb-2 px-6 divide-y divide-gray-100 dark:divide-gray-700">


          <!-- <component :key="index" v-for="(field, index) in resource.fields" :index="index"
            :is="`detail-${field.component}`" :resource-name="resourceName" :resource-id="resourceId" :resource="resource"
            :field="field" /> -->


          <div class="flex flex-col md:flex-row -mx-6 px-6 py-2 md:py-0 space-y-2 md:space-y-0" :key="index"
            v-for="(field, index) in resource.fields" :index="index">
            <div class="md:w-1/4 md:py-3 max-w-xs">
              <h4 class="font-bold"><span>{{ field.name }}</span></h4>
            </div>
            <div class="md:w-3/4 md:py-3 break-all lg:break-words max-w-2xl">
              <div>
                <div>

                  <button v-for="value in field.value" type="button"
                    class=" w-full flex items-center text-left rounded px-1 py-1 !cursor-default">
                    <div class="flex items-center space-x-3">
                      <div>
                        <p class="text-xs font-semibold">{{ value.display }}</p>
                      </div>
                    </div>
                  </button>

                </div>
              </div>
            </div>
          </div>



        </div>
      </div>
    </td>
  </Teleport>
</template>

<script>

export default {
  props: ['resourceName',
    'field'],

  data: () => ({
    loading: true,
    title: null,
    resource: null,


    isMounted: false,
    columnCount: 1,
    rowExpanded: false,
  }),


  mounted() {
    this.$nextTick(() => {
    
      // Create a table row to teleport the expanded row to
      this.createTableRow();
    });
  },
  methods: {
    createTableRow() {
      const indexField = this.$refs.indexField;
      const tr = indexField.closest('tr');
      const td = indexField.closest('td');


      // TODO: remove the column if moving trigger to actions
      // const siblings = Array.from(tr.children);    
      // const index = siblings.indexOf(td);
      // td.remove();
      // const th = tr.closest('table').querySelector('thead > tr');
      // console.log('th', th);

      this.columnCount = tr.querySelectorAll('td').length;
      const trNext = document.createElement('tr');
      trNext.classList.add('bg-gray-100');
      trNext.classList.add('dark:bg-gray-800');

      tr.insertAdjacentElement('afterend', trNext);
      this.to = trNext;
      

      const actionItmes = tr.querySelector('.flex.items-center.justify-end.space-x-0.text-gray-400');
      this.toggleTeleportTarget = actionItmes;

      
      this.isMounted = true;

    },

    toggleExpandedRow() {
      this.rowExpanded = !this.rowExpanded;
      if (this.field.expandingData) {

        console.log('this.field.expandingData');
        console.log(this.field.expandingData);
        this.resource = {
          fields: [],
        };
        this.resource.fields = this.field.expandingData;
        this.loading = false;
      } else if (!this.resource) {
        this.getResource();
      }
    },

    getResource() {
      Nova.request().get(
        `/nova-api/${this.resourceName}/${this.$parent.resource.id.value}/preview`
      )
        .then(({ data: { title, resource } }) => {
          this.title = title
          this.resource = resource
          this.loading = false
        })
        .catch(error => {
          console.log(error);

          if (error.response.status >= 500) {
            Nova.$emit('error', error.response.data.message)
            return
          }

          if (error.response.status === 404) {
            Nova.visit('/404')
            return
          }

          if (error.response.status === 403) {
            Nova.visit('/403')
            return
          }

          if (error.response.status === 401) return Nova.redirectToLogin()

          Nova.error(this.__('This resource no longer exists'))

          Nova.visit(`/resources/${this.resourceName}`)
        })
    }

  },
}
</script>
<style>
.rotate-180 {
  transform: rotate(180deg);
}
</style>
