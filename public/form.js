new Vue({
    el: '#form',
    data: {
        phone: '',
        name: '',
        tariff_id: 0,
        delivery_date_start: '',
        tariffs: tariffs,
        lang: lang,
        errors: {},
        success: false
    },

    components: {
        vuejsDatepicker
    },

    computed: {
        disabledDays: function() {
            let constraints = {
                to: new Date()
            };

            if (this.tariffs && this.tariffs[this.tariff_id]) {
                let availableDays = this.tariffs[this.tariff_id].delivery_days;
                let disabledDays = {};

                for (let i = 0; i < 7; i++) {
                    disabledDays[i] = i;
                }

                for (let i = 0; i < availableDays.length; i++) {
                    delete disabledDays[availableDays[i].week_day % 7];
                }

                constraints.days = Object.values(disabledDays);
            }

            return constraints;
        }
    },

    updated: function() {
        if (!this.delivery_date_start) {
            return;
        }

        if (!this.disabledDays.days) {
            return;
        }

        if (this.disabledDays.days.indexOf(this.delivery_date_start.getDay()) !== -1) {
            this.delivery_date_start = '';
        }
    },

    methods: {
        submit: function() {
            let self = this;

            fetch('/orders', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json; charset=utf-8',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.head.querySelector('[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    name: this.name,
                    phone: this.phone,
                    tariff_id: this.tariff_id,
                    delivery_date_start: this.delivery_date_start
                })
            }).then(function(response) {
                return response.json();
            }).then(function(response) {
                self.errors = response.errors || {};
                self.success = !!response.id;
            });
        }
    },

    template: `
        <div class="form" style="max-width: 560px; margin: 3rem auto;">
            <div class="alert alert-success" role="alert" v-if="success">
                Заказ создан!
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Ваше имя
                </label>

                <input type="name" :class="'form-control' + (errors.name ? ' is-invalid' : '')" v-model="name" required>

                <div class="invalid-feedback" v-if="errors.name">
                    {{ errors.name[0] }}
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Ваш телефон
                </label>

                <input type="phone" :class="'form-control' + (errors.phone ? ' is-invalid' : '')" v-model="phone" required>

                <div class="invalid-feedback" v-if="errors.phone">
                    {{ errors.phone[0] }}
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Тариф
                </label>

                <select name="tariff_id" :class="'form-control' + (errors.tariff_id ? ' is-invalid' : '')" v-model="tariff_id" required>
                    <option value="" selected>Выберите тариф</option>
                    <option v-for="tariff in tariffs" :value="tariff.id">
                        {{ tariff.title }}
                    </option>
                </select>

                <div class="invalid-feedback" v-if="errors.tariff_id">
                    {{ errors.tariff_id[0] }}
                </div>
            </div>

            <div class="mb-3" v-if="tariff_id">
                <label class="form-label">
                    День доставки
                </label>

                <div :class="errors.delivery_date_start ? ' is-invalid' : ''">
                    <vuejs-datepicker
                        :disabled-dates="disabledDays"
                        format="dd.MM.yyyy"
                        v-model="delivery_date_start"
                        input-class="form-control"
                        :monday-first="true"
                        :required="true"
                    ></vuejs-datepicker>
                </div>

                <div class="invalid-feedback" v-if="errors.delivery_date_start">
                    {{ errors.delivery_date_start[0] }}
                </div>
            </div>

            <button type="submit" class="btn btn-primary" v-on:click="submit">
                Заказать
            </button>
        </div>
    `
});
