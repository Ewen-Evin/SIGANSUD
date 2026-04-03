package com.example.ppe4;

import android.content.Context;

import androidx.test.ext.junit.runners.AndroidJUnit4;
import androidx.test.platform.app.InstrumentationRegistry;

import com.sigansud.app.utils.SessionManager;

import org.junit.Test;
import org.junit.runner.RunWith;

import static org.junit.Assert.*;

/**
 * Tests instrumentés — nécessitent un émulateur ou un vrai device.
 */
@RunWith(AndroidJUnit4.class)
public class ExampleInstrumentedTest {

    @Test
    public void useAppContext() {
        Context appContext = InstrumentationRegistry.getInstrumentation().getTargetContext();
        assertEquals("com.sigansud.app", appContext.getPackageName());
    }

    @Test
    public void sessionManager_notLoggedIn_byDefault() {
        Context appContext = InstrumentationRegistry.getInstrumentation().getTargetContext();
        // Nettoyer la session avant le test
        SessionManager session = new SessionManager(appContext);
        session.logout();
        assertFalse(session.isLoggedIn());
    }
}