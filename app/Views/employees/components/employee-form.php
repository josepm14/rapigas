<div x-data="employeeForm" class="space-y-6">
    <!-- Personal Information Section -->
    <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <h3 class="text-lg font-medium leading-6 text-gray-900">
                    Información Personal
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Complete los datos del empleado.
                </p>
            </div>

            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="grid grid-cols-6 gap-6">
                    <!-- Nombre -->
                    <div class="col-span-6 sm:col-span-3">
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Nombre Completo
                        </label>
                        <input type="text" 
                               id="name" 
                               x-model="form.name"
                               @input="validateField('name')"
                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                               :class="{'border-red-300': errors.name}">
                        <div x-show="errors.name" 
                             class="mt-1 text-sm text-red-600" 
                             x-text="errors.name"></div>
                    </div>

                    <!-- DNI -->
                    <div class="col-span-6 sm:col-span-3">
                        <label for="dni" class="block text-sm font-medium text-gray-700">
                            DNI
                        </label>
                        <input type="text" 
                               id="dni" 
                               x-model="form.dni"
                               @input="validateField('dni')"
                               maxlength="8"
                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                               :class="{'border-red-300': errors.dni}">
                        <div x-show="errors.dni" 
                             class="mt-1 text-sm text-red-600" 
                             x-text="errors.dni"></div>
                    </div>

                    <!-- Email -->
                    <div class="col-span-6 sm:col-span-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Correo Electrónico
                        </label>
                        <input type="email" 
                               id="email" 
                               x-model="form.email"
                               @input="validateField('email')"
                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                               :class="{'border-red-300': errors.email}">
                        <div x-show="errors.email" 
                             class="mt-1 text-sm text-red-600" 
                             x-text="errors.email"></div>
                    </div>

                    <!-- Phone -->
                    <div class="col-span-6 sm:col-span-3">
                        <label for="phone" class="block text-sm font-medium text-gray-700">
                            Teléfono
                        </label>
                        <input type="tel" 
                               id="phone" 
                               x-model="form.phone"
                               @input="validateField('phone')"
                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                               :class="{'border-red-300': errors.phone}">
                        <div x-show="errors.phone" 
                             class="mt-1 text-sm text-red-600" 
                             x-text="errors.phone"></div>
                    </div>

                    <!-- Department -->
                    <div class="col-span-6 sm:col-span-3">
                        <label for="department" class="block text-sm font-medium text-gray-700">
                            Departamento
                        </label>
                        <select id="department" 
                                x-model="form.department_id"
                                @change="validateField('department_id')"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                :class="{'border-red-300': errors.department_id}">
                            <option value="">Seleccione...</option>
                            <template x-for="dept in departments" :key="dept.id">
                                <option :value="dept.id" x-text="dept.name"></option>
                            </template>
                        </select>
                        <div x-show="errors.department_id" 
                             class="mt-1 text-sm text-red-600" 
                             x-text="errors.department_id"></div>
                    </div>

                    <!-- Status -->
                    <div class="col-span-6 sm:col-span-3">
                        <label for="status" class="block text-sm font-medium text-gray-700">
                            Estado
                        </label>
                        <select id="status" 
                                x-model="form.status"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="active">Activo</option>
                            <option value="inactive">Inactivo</option>
                        </select>
                    </div>

                    <!-- Password Fields (Only show if isNew) -->
                    <template x-if="isNew">
                        <div class="col-span-6 grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="password" class="block text-sm font-medium text-gray-700">
                                    Contraseña
                                </label>
                                <input type="password" 
                                       id="password" 
                                       x-model="form.password"
                                       @input="validateField('password')"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       :class="{'border-red-300': errors.password}">
                                <div x-show="errors.password" 
                                     class="mt-1 text-sm text-red-600" 
                                     x-text="errors.password"></div>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                    Confirmar Contraseña
                                </label>
                                <input type="password" 
                                       id="password_confirmation" 
                                       x-model="form.password_confirmation"
                                       @input="validateField('password_confirmation')"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="flex justify-end space-x-3">
        <button type="button"
                @click="cancel"
                class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Cancelar
        </button>
        <button type="submit"
                :disabled="saving || hasErrors"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
            <svg x-show="saving" 
                 class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" 
                 fill="none" 
                 viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
            </svg>
            <span x-text="submitButtonText"></span>
        </button>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('employeeForm', () => ({
        isNew: true,
        saving: false,
        departments: [],
        form: {
            name: '',
            dni: '',
            email: '',
            phone: '',
            department_id: '',
            status: 'active',
            password: '',
            password_confirmation: ''
        },
        errors: {},

        init() {
            this.isNew = !this.$el.getAttribute('data-employee');
            if (!this.isNew) {
                const employee = JSON.parse(this.$el.getAttribute('data-employee'));
                this.form = {
                    ...employee,
                    department_id: employee.department_id.toString()
                };
            }
            this.loadDepartments();
        },

        get submitButtonText() {
            if (this.saving) return 'Guardando...';
            return this.isNew ? 'Crear Empleado' : 'Guardar Cambios';
        },

        get hasErrors() {
            return Object.keys(this.errors).length > 0;
        },

        async loadDepartments() {
            try {
                const response = await fetch('/departments');
                const data = await response.json();
                if (data.success) {
                    this.departments = data.departments;
                }
            } catch (error) {
                console.error('Error loading departments:', error);
            }
        },

        validateField(field) {
            delete this.errors[field];

            switch (field) {
                case 'name':
                    if (!this.form.name) {
                        this.errors.name = 'El nombre es requerido';
                    }
                    break;
                case 'dni':
                    if (!this.form.dni) {
                        this.errors.dni = 'El DNI es requerido';
                    } else if (!/^\d{8}$/.test(this.form.dni)) {
                        this.errors.dni = 'El DNI debe tener 8 dígitos';
                    }
                    break;
                case 'email':
                    if (!this.form.email) {
                        this.errors.email = 'El email es requerido';
                    } else if (!/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(this.form.email)) {
                        this.errors.email = 'Email inválido';
                    }
                    break;
                case 'phone':
                    if (this.form.phone && !/^\d{9}$/.test(this.form.phone)) {
                        this.errors.phone = 'El teléfono debe tener 9 dígitos';
                    }
                    break;
                case 'department_id':
                    if (!this.form.department_id) {
                        this.errors.department_id = 'El departamento es requerido';
                    }
                    break;
                case 'password':
                    if (this.isNew && !this.form.password) {
                        this.errors.password = 'La contraseña es requerida';
                    } else if (this.form.password && this.form.password.length < 6) {
                        this.errors.password = 'La contraseña debe tener al menos 6 caracteres';
                    }
                    break;
                case 'password_confirmation':
                    if (this.form.password !== this.form.password_confirmation) {
                        this.errors.password = 'Las contraseñas no coinciden';
                    }
                    break;
            }
        },

        validateForm() {
            ['name', 'dni', 'email', 'department_id'].forEach(field => {
                this.validateField(field);
            });
            if (this.isNew) {
                this.validateField('password');
                this.validateField('password_confirmation');
            }
            return !this.hasErrors;
        },

        cancel() {
            window.location.href = '/employees';
        }
    }));
});
</script>