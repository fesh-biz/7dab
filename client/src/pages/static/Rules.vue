<template>
  <q-card flat class="q-mt-lg">
    <q-card-section>
      <q-timeline color="positive">
        <q-timeline-entry heading>
          Наші правила
        </q-timeline-entry>

        <!-- Мова -->
        <q-timeline-entry
          title="Мова"
        >
          <q-list dense>
            <q-item>
              <q-item-section>
                Мова публікації має бути українська
              </q-item-section>
            </q-item>
            <q-item>
              <q-item-section>
                Мова коментарів може бути будь якою
              </q-item-section>
            </q-item>
          </q-list>
        </q-timeline-entry>

        <!-- Публікація -->
        <q-timeline-entry
          title="Публікація"
        >
          <q-list dense>
            <q-item>
              <q-item-section>
                <strong>Забороняється публікувати:</strong>
              </q-item-section>
            </q-item>
            <q-item>
              <q-item-section>
                <q-list dense>
                  <!-- Особисті дані (без згоди особи): -->
                  <q-item>
                    <q-item-section>
                      <strong>Особисті дані (без згоди особи):</strong>
                    </q-item-section>
                  </q-item>
                  <q-item>
                    <q-item-section>
                      Особисті дані, такі як: ім'я, адреса тощо <br>
                      Контактні дані, такі як: посилання на соц мережі чи номери телефонів тощо
                      <q-separator spaced />
                    </q-item-section>
                  </q-item>

                  <!-- На період війни та в подальшому: -->
                  <q-item>
                    <q-item-section>
                      <strong>На період війни та в подальшому:</strong>
                    </q-item-section>
                  </q-item>
                  <q-item>
                    <q-item-section>
                      Дані наших військових, якщо цих даних немає у відкритому доступі у акредитованих ЗМІ <br>
                      Дані, які можуть дозволити виявити позиції наших військових <br>
                      Будь які дані роботи нашої ППО <br>
                      Інші дані, які будь яким чином можуть бути використані ворогом на його користь
                      <q-separator spaced />
                    </q-item-section>
                  </q-item>
                </q-list>
              </q-item-section>
            </q-item>
          </q-list>
        </q-timeline-entry>

        <!-- Реєстрація та облікові дані -->
        <q-timeline-entry
          title="Реєстрація та облікові дані"
        >
          <q-list dense>
            <q-item>
              <q-item-section>
                <strong>Забороняється:</strong>
              </q-item-section>
            </q-item>
            <q-item>
              <q-item-section>
                Видавати себе за іншу особу <br>
                Мати більш ніж один акаунт <br>
                Мати образливий логін, байдуже на що він спрямован: групу осіб, або людину
                <q-separator spaced />
              </q-item-section>
            </q-item>
          </q-list>
        </q-timeline-entry>

        <q-timeline-entry
          title="Адміністрація сайту може додавати або редагувати правила в будь який час."
        />
      </q-timeline>
    </q-card-section>
  </q-card>
</template>

<script>
export default {
  name: 'Rules',

  data () {
    return {}
  }
}
</script>
