<template>
    <section class="container p-6 mx-auto lg:max-w-6xl">
        <FullCalendar :options="calendarOptions" />
    </section>
</template>

<script>
import "@fullcalendar/core/vdom"; // solves problem with Vite
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import timeGrid from "@fullcalendar/timegrid";

export default {
  name: "CalendarView",
  components: {
    FullCalendar, // make the <FullCalendar> tag available
  },
  data() {
    return {
      calendarOptions: {
        themeSystem: "standard",
        locale: "fr",
        plugins: [dayGridPlugin, timeGrid, interactionPlugin],
        initialView: "timeGridWeek", //dayGridMonth
        dateClick: this.handleDateClick,
        events: [
          { title: "event 1", date: "2019-04-01" },
          { title: "event 2", date: "2019-04-02" },
        ],
      },
    };
  },
  methods: {
    handleDateClick: function (arg) {
      alert("date click! " + arg.dateStr);
    },
  },
};
</script>

<style scoped></style>
