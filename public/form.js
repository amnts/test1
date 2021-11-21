new Vue({
    el: '#form',
    data: {
        phone: '',
        name: '',
        tariff_id: 0,
        delivery_day: '',
        tariffs: tariffs,
        lang: lang,
        errors: {},
        success: false
    },

    computed: {
        currentTariff: function() {
            if (this.tariffs && this.tariffs[this.tariff_id]) {
                return this.tariffs[this.tariff_id];
            }

            return {};
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
                    delivery_day: this.delivery_day
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

                <input type="name" :class="'form-control' + (errors.name ? ' is-invalid' : '')" v-model="name">

                <div class="invalid-feedback" v-if="errors.name">
                    {{ errors.name[0] }}
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Ваш телефон
                </label>

                <input type="phone" :class="'form-control' + (errors.phone ? ' is-invalid' : '')" v-model="phone">

                <div class="invalid-feedback" v-if="errors.phone">
                    {{ errors.phone[0] }}
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Тариф
                </label>

                <select name="tariff_id" :class="'form-control' + (errors.tariff_id ? ' is-invalid' : '')" v-model="tariff_id">
                    <option value="0" selected>Выберите тариф</option>
                    <option v-for="tariff in tariffs" :value="tariff.id">
                        {{ tariff.title }}
                    </option>
                </select>

                <div class="invalid-feedback" v-if="errors.tariff_id">
                    {{ errors.tariff_id[0] }}
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    День доставки
                </label>

                <select name="delivery_day" :class="'form-control' + (errors.delivery_day ? ' is-invalid' : '')" v-model="delivery_day">
                    <option value="">Выберите день доставки</option>
                    <option v-for="day in currentTariff.delivery_days" :value="day.week_day">
                        {{ lang[day.week_day] }}
                    </option>
                </select>

                <div class="invalid-feedback" v-if="errors.delivery_day">
                    {{ errors.delivery_day[0] }}
                </div>
            </div>

            <button type="submit" class="btn btn-primary" v-on:click="submit">
                Заказать
            </button>
        </div>
    `
});
