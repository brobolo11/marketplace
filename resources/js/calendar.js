import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import esLocale from '@fullcalendar/core/locales/es';

// Hacer Calendar disponible globalmente
window.FullCalendar = { Calendar };

// Configuración por defecto de Fullcalendar
export function initializeCalendar(elementId, options = {}) {
    const calendarEl = document.getElementById(elementId);
    
    if (!calendarEl) {
        console.error(`Element with id "${elementId}" not found`);
        return null;
    }

    const defaultOptions = {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        locale: esLocale,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth'
        },
        selectable: true,
        selectMirror: true,
        weekends: true,
        editable: false,
        dayMaxEvents: true,
        validRange: {
            start: new Date() // No permitir fechas pasadas
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día'
        },
        height: 'auto',
        contentHeight: 'auto',
    };

    const calendar = new Calendar(calendarEl, {
        ...defaultOptions,
        ...options
    });

    calendar.render();
    return calendar;
}

// Función para formatear fechas
export function formatDate(date, format = 'long') {
    const d = typeof date === 'string' ? new Date(date) : date;
    
    const formats = {
        short: { day: 'numeric', month: 'short' },
        long: { day: 'numeric', month: 'long', year: 'numeric' },
        full: { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }
    };

    return d.toLocaleDateString('es-ES', formats[format] || formats.long);
}

// Función para cargar disponibilidad de un profesional
export async function loadProfessionalAvailability(professionalId) {
    try {
        const response = await fetch(`/api/availability/${professionalId}`);
        if (!response.ok) {
            throw new Error('Error loading availability');
        }
        return await response.json();
    } catch (error) {
        console.error('Error loading professional availability:', error);
        return { available: [], blocked: [] };
    }
}

// Función para cargar reservas existentes
export async function loadBookings(professionalId) {
    try {
        const response = await fetch(`/api/bookings/${professionalId}/dates`);
        if (!response.ok) {
            throw new Error('Error loading bookings');
        }
        return await response.json();
    } catch (error) {
        console.error('Error loading bookings:', error);
        return [];
    }
}

// Función para validar si una fecha está disponible
export function isDateAvailable(date, blockedDates = []) {
    const dateStr = typeof date === 'string' ? date : date.toISOString().split('T')[0];
    return !blockedDates.includes(dateStr);
}

// Función para crear un evento de calendario
export function createCalendarEvent(title, date, backgroundColor = '#3b82f6', textColor = '#ffffff') {
    return {
        title,
        start: date,
        backgroundColor,
        borderColor: backgroundColor,
        textColor,
        display: 'background'
    };
}

// Exportar para uso global
window.CalendarUtils = {
    initializeCalendar,
    formatDate,
    loadProfessionalAvailability,
    loadBookings,
    isDateAvailable,
    createCalendarEvent
};
