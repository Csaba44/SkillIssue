import { describe, it, expect, vi, beforeEach } from 'vitest';
import router from '../../config/router';
import { useUserStore } from '../../stores/UserStore';
import { setActivePinia, createPinia } from 'pinia';

vi.mock('../../stores/UserStore', () => ({
    useUserStore: vi.fn(),
}));

describe('Router Guards', () => {
    let userStore;

    beforeEach(async () => {
        setActivePinia(createPinia());

        userStore = {
            isAuthenticated: true,
            user: { is_admin: false },
            verifySession: vi.fn().mockResolvedValue(true),
        };

        useUserStore.mockReturnValue(userStore);
        await router.push('/');
    });

    it('1. Védett oldalra navigálás: ha nem authenticated, akkor /login-ra dob', async () => {
        userStore.isAuthenticated = false;

        await router.push('/dashboard');

        expect(router.currentRoute.value.fullPath).toBe('/login');
    });

    it('2. Védett oldalra navigálás: ha null az állapot, hívja a verifySession-t', async () => {
        userStore.isAuthenticated = null;

        await router.push('/dashboard');

        expect(userStore.verifySession).toHaveBeenCalled();
    });

    it('3. Admin oldal: ha nem admin, akkor /-re dob', async () => {
        userStore.isAuthenticated = true;
        userStore.user = { is_admin: false };

        await router.push('/admin');

        expect(router.currentRoute.value.fullPath).toBe('/');
    });

    it('4. Admin oldal: ha admin, akkor beenged', async () => {
        userStore.isAuthenticated = true;
        userStore.user = { is_admin: true };

        await router.push('/admin');

        expect(router.currentRoute.value.fullPath).toBe('/admin');
    });
});