<script setup>
import { computed, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import NoPermiso from '@/Components/NoPermiso.vue';

const props = defineProps({
  title: String,
  funcionalidad: String,
  routeName: String,
  items: { type: Array, default: () => [] },
  columns: { type: Array, default: () => [] },
  fields: { type: Array, default: () => [] },
  options: { type: Object, default: () => ({}) },
  itemLabel: { type: String, default: 'registro' },
});

const page = usePage();
const privilegios = computed(() => page.props.auth?.privilegios?.[props.funcionalidad] || {});
const canRead = computed(() => !!privilegios.value.leer);
const canAdd = computed(() => !!privilegios.value.agregar);
const canEdit = computed(() => !!privilegios.value.modificar);
const canDelete = computed(() => !!privilegios.value.borrar);
const errors = computed(() => page.props.errors || {});

const showAdd = ref(false);
const showEdit = ref(null);
const showDelete = ref(null);
const newForm = ref({});
const editForm = ref({});

function getValue(item, path) {
  if (!path) return '';
  return path.split('.').reduce((acc, key) => (acc === null || acc === undefined ? '' : acc[key]), item) ?? '';
}

function formatValue(item, col) {
  const value = getValue(item, col.key);
  if (col.format === 'money') return `Bs ${Number(value || 0).toFixed(2)}`;
  if (col.format === 'date') return value || '—';
  return value || '—';
}

function defaultForm() {
  const data = {};
  props.fields.forEach((field) => {
    if (field.default !== undefined) data[field.key] = field.default;
    else if (field.type === 'number') data[field.key] = 0;
    else data[field.key] = '';
  });
  return data;
}

function openAdd() {
  newForm.value = defaultForm();
  showAdd.value = true;
}

function openEdit(item) {
  const data = {};
  props.fields.forEach((field) => {
    data[field.key] = item[field.key] ?? (field.type === 'number' ? 0 : '');
  });
  editForm.value = data;
  showEdit.value = item;
}

function payload(data) {
  const clean = { ...data };
  Object.keys(clean).forEach((key) => {
    if (clean[key] === '') clean[key] = null;
  });
  return clean;
}

function store() {
  router.post(route(`${props.routeName}.store`), payload(newForm.value), {
    preserveScroll: true,
    onSuccess: () => {
      showAdd.value = false;
      newForm.value = defaultForm();
    },
  });
}

function update() {
  router.put(route(`${props.routeName}.update`, showEdit.value.id), payload(editForm.value), {
    preserveScroll: true,
    onSuccess: () => {
      showEdit.value = null;
    },
  });
}

function destroyItem() {
  router.delete(route(`${props.routeName}.destroy`, showDelete.value.id), {
    preserveScroll: true,
    onSuccess: () => {
      showDelete.value = null;
    },
  });
}
</script>

<template>
  <AppLayout :title="title">
    <section class="content" v-if="canRead">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h1 class="card-title mb-0">
          <i class="fas fa-boxes mr-2"></i><b>{{ title.toUpperCase() }}</b>
        </h1>
        <button v-if="canAdd" class="btn btn-success ml-auto" @click="openAdd">
          <i class="fa fa-plus"></i>&nbsp; Agregar
        </button>
      </div>

      <div class="card table-responsive mt-3">
        <div class="card-body">
          <table class="table table-hover table-sm align-middle">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th v-for="col in columns" :key="col.key">{{ col.label }}</th>
                <th class="text-center">ACCIONES</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in items" :key="item.id">
                <td>{{ item.id }}</td>
                <td v-for="col in columns" :key="col.key">{{ formatValue(item, col) }}</td>
                <td class="text-center">
                  <a v-if="canEdit" href="#" @click.prevent="openEdit(item)" title="Editar">
                    <i class="fa fa-edit"></i>
                  </a>
                  <span v-if="canEdit && canDelete">&nbsp;|&nbsp;</span>
                  <a v-if="canDelete" href="#" @click.prevent="showDelete = item" title="Eliminar">
                    <i class="fa fa-trash text-danger"></i>
                  </a>
                </td>
              </tr>
              <tr v-if="!items.length">
                <td :colspan="columns.length + 2" class="text-center text-muted py-4">
                  No existen registros activos.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div v-if="showAdd" class="modal-mask">
        <div class="modal-container">
          <div class="modal-header d-flex justify-content-between align-items-center">
            <h5 class="modal-title">Agregar {{ itemLabel }}</h5>
            <button type="button" class="btn-close" @click="showAdd = false"></button>
          </div>
          <form @submit.prevent="store">
            <div class="modal-body">
              <div class="mb-3" v-for="field in fields" :key="field.key">
                <label class="form-label">{{ field.label }}</label>
                <select v-if="field.type === 'select'" class="form-select" v-model="newForm[field.key]">
                  <option value="">Seleccione</option>
                  <option v-for="option in options[field.optionsKey] || []" :key="option.id" :value="option.id">
                    {{ getValue(option, field.optionLabel || 'nombre') || ('#' + option.id) }}
                  </option>
                </select>
                <textarea v-else-if="field.type === 'textarea'" class="form-control" v-model="newForm[field.key]" rows="2"></textarea>
                <input v-else :type="field.type || 'text'" :step="field.type === 'number' ? 'any' : undefined" class="form-control" v-model="newForm[field.key]" />
                <div v-if="errors[field.key]" class="text-danger small">{{ errors[field.key] }}</div>
              </div>
            </div>
            <div class="modal-footer text-end">
              <button type="button" class="btn btn-secondary" @click="showAdd = false">Cancelar</button>
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>
        </div>
      </div>

      <div v-if="showEdit" class="modal-mask">
        <div class="modal-container">
          <div class="modal-header d-flex justify-content-between align-items-center">
            <h5 class="modal-title">Editar {{ itemLabel }}</h5>
            <button type="button" class="btn-close" @click="showEdit = null"></button>
          </div>
          <form @submit.prevent="update">
            <div class="modal-body">
              <div class="mb-3" v-for="field in fields" :key="field.key">
                <label class="form-label">{{ field.label }}</label>
                <select v-if="field.type === 'select'" class="form-select" v-model="editForm[field.key]">
                  <option value="">Seleccione</option>
                  <option v-for="option in options[field.optionsKey] || []" :key="option.id" :value="option.id">
                    {{ getValue(option, field.optionLabel || 'nombre') || ('#' + option.id) }}
                  </option>
                </select>
                <textarea v-else-if="field.type === 'textarea'" class="form-control" v-model="editForm[field.key]" rows="2"></textarea>
                <input v-else :type="field.type || 'text'" :step="field.type === 'number' ? 'any' : undefined" class="form-control" v-model="editForm[field.key]" />
                <div v-if="errors[field.key]" class="text-danger small">{{ errors[field.key] }}</div>
              </div>
            </div>
            <div class="modal-footer text-end">
              <button type="button" class="btn btn-secondary" @click="showEdit = null">Cancelar</button>
              <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
          </form>
        </div>
      </div>

      <div v-if="showDelete" class="modal-mask">
        <div class="modal-container text-center">
          <h5>¿Eliminar {{ itemLabel }}?</h5>
          <p>Esta acción desactivará el registro <strong>#{{ showDelete.id }}</strong>.</p>
          <div class="modal-footer text-end">
            <button class="btn btn-secondary" @click="showDelete = null">Cancelar</button>
            <button class="btn btn-danger" @click="destroyItem">Eliminar</button>
          </div>
        </div>
      </div>
    </section>

    <NoPermiso v-else :mensaje="`No tienes permisos para ver ${title}.`" />
  </AppLayout>
</template>

<style scoped>
.modal-mask {
  position: fixed;
  z-index: 9999;
  background: rgba(0, 0, 0, 0.35);
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}
.modal-container {
  background: white;
  width: min(720px, 94%);
  max-height: 92vh;
  overflow: auto;
  border-radius: 8px;
  padding: 20px;
}
</style>
