import { shallowMount } from '@vue/test-utils';
import { describe, it, expect, vi, beforeEach } from 'vitest';
import QuestionReport from '../Report/QuestionReport.vue';
import Button from '../Generic/Button.vue';
import api from '../../config/api';
import { toast } from 'vue-sonner';

vi.mock('../../config/api', () => ({
  default: {
    post: vi.fn(() => Promise.resolve({ data: { success: true } })),
  }
}));

vi.mock('vue-sonner', () => ({
  toast: {
    error: vi.fn(),
    promise: vi.fn((promise) => promise),
  }
}));

describe('QuestionReport.vue', () => {
  const mockProps = {
    questionToken: 'token-123',
    question: 'Teszt?',
    answers: [{ id: 1, answer: 'A' }]
  };

  it('1. Render teszt', () => {
    const wrapper = shallowMount(QuestionReport, { props: mockProps });
    expect(wrapper.exists()).toBe(true);
  });

  it('2. Hibát dob üres kommentre', async () => {
    const wrapper = shallowMount(QuestionReport, { props: mockProps });
    await wrapper.findComponent(Button).trigger('click');
    expect(toast.error).toHaveBeenCalled();
  });
});