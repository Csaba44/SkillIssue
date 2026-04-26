import { mount } from '@vue/test-utils';
import { describe, it, expect, vi } from 'vitest';
import { createTestingPinia } from '@pinia/testing';
import RankedWidget from '../Game/RankedWidget.vue';
import { useGameStore } from '../../stores/GameStore';

describe('RankedWidget.vue', () => {
  const mountComponent = () => mount(RankedWidget, {
    global: {
      plugins: [
        createTestingPinia({
          createSpy: vi.fn,
          initialState: {
            counter: {
              isAuthenticated: true,
              user: { id: 1, name: 'Teszt' }
            },
            game: {
              match: {
                match_uuid: '123',
                playerA: { userId: 1 },
                playerB: { userId: 2 }
              },
              currentAnswers: [{ id: 1, text: 'Válasz 1' }],
              currentQuestion: { id: 1, text: 'Kérdés?' },
              selectedAnswer: null
            }
          },
        }),
      ],
    },
  });

  it('1. Render teszt', async () => {
    const wrapper = mountComponent();
    await wrapper.vm.$nextTick();

    expect(wrapper.exists()).toBe(true);
  });

  it('2. Beküldéskor meghívja a gameStore.submitAnswer-t', async () => {
    const wrapper = mountComponent();
    await wrapper.vm.$nextTick();

    const gameStore = useGameStore();

    gameStore.selectedAnswer = 1;

    const button = wrapper.find('button.bg-linear-to-r');

    if (!button.exists()) {
      throw new Error("A teszt nem találta a gombot. Ellenőrizd az osztálynevet!");
    }

    await button.trigger('click');
    expect(gameStore.submitAnswer).toHaveBeenCalledWith(1);
  });
});