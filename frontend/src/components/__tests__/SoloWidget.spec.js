import { mount } from '@vue/test-utils';
import { describe, it, expect, vi, beforeEach } from 'vitest';
import { createTestingPinia } from '@pinia/testing';
import SoloWidget from '../Game/SoloWidget.vue';
import AnswerButton from '../Game/AnswerButton.vue';
import Button from '../Generic/Button.vue';
import { toast } from 'vue-sonner';

vi.mock('vue-sonner', () => ({
    toast: { error: vi.fn(), warning: vi.fn(), info: vi.fn() }
}));

vi.stubGlobal('open', vi.fn());

describe('SoloWidget.vue', () => {
    const mountComponent = (props = {}) => mount(SoloWidget, {
        props: {
            question: 'Teszt kérdés?',
            subject: 'Történelem',
            answers: [{ id: 1, answer: 'Válasz A' }, { id: 2, answer: 'Válasz B' }],
            currRoundNumber: 1,
            totalRounds: '10',
            correctAnswerId: null,
            questionToken: 'token-123',
            ...props
        },
        global: {
            plugins: [
                createTestingPinia({
                    createSpy: vi.fn,
                    initialState: {
                        counter: {
                            isAuthenticated: true,
                            user: { name: 'Teszt Felhasználó' }
                        }
                    },
                }),
            ],
        },
    });

    beforeEach(() => {
        vi.clearAllMocks();
    });

    it('1. Render teszt', () => {
        const wrapper = mountComponent();
        expect(wrapper.text()).toContain('Teszt kérdés?');
        expect(wrapper.text()).toContain('Teszt Felhasználó');
    });

    it('2. Beküldéskor emiteli az onGetNextQuestion eseményt', async () => {
        const wrapper = mountComponent();

        await wrapper.findAllComponents(AnswerButton)[0].trigger('click');

        await wrapper.findComponent(Button).trigger('click');

        expect(wrapper.emitted()).toHaveProperty('onGetNextQuestion');
        expect(wrapper.emitted('onGetNextQuestion')[0]).toEqual([1]);
    });

    it('3. Üres válasznál hibát dob a "Következő" gombra', async () => {
        const wrapper = mountComponent();

        await wrapper.findComponent(Button).trigger('click');

        expect(toast.error).toHaveBeenCalledWith("Nincs kiválasztott válasz.");
        expect(wrapper.emitted('onGetNextQuestion')).toBeUndefined();
    });

    it('4. Bejelentés gomb megnyitja az ablakot', async () => {
        vi.useFakeTimers();
        const wrapper = mountComponent();

        const reportButton = wrapper.find('button.cursor-pointer');
        await reportButton.trigger('click');

        expect(toast.info).toHaveBeenCalled();

        vi.runAllTimers();

        expect(window.open).toHaveBeenCalled();
        expect(window.open.mock.calls[0][0]).toContain('/report/question');

        vi.useRealTimers();
    });
});