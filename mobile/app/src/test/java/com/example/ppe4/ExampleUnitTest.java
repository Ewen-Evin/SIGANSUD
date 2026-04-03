package com.example.ppe4;

import com.sigansud.app.utils.DateUtils;

import org.junit.Test;
import static org.junit.Assert.*;

/**
 * Tests unitaires locaux (ne nécessitent pas d'émulateur).
 */
public class ExampleUnitTest {

    @Test
    public void dateUtils_todayApi_notEmpty() {
        String today = DateUtils.todayApi();
        assertNotNull(today);
        assertFalse(today.isEmpty());
        // Format YYYY-MM-DD = 10 caractères
        assertEquals(10, today.length());
    }

    @Test
    public void dateUtils_apiToDisplay_correctFormat() {
        String display = DateUtils.apiToDisplay("2026-03-13");
        assertEquals("13/03/2026", display);
    }

    @Test
    public void dateUtils_apiToDisplay_nullSafe() {
        String display = DateUtils.apiToDisplay(null);
        assertEquals("", display);
    }

    @Test
    public void dateUtils_formatDateTime_withHeure() {
        String result = DateUtils.formatDateTime("2026-03-13", "14:30");
        assertEquals("13/03/2026 à 14:30", result);
    }

    @Test
    public void dateUtils_formatDateTime_withoutHeure() {
        String result = DateUtils.formatDateTime("2026-03-13", null);
        assertEquals("13/03/2026", result);
    }
}