import { shallowMount } from '@vue/test-utils';
import { describe, it, expect, vi, beforeEach } from 'vitest';
import QuestionReport from '../Report/QuestionReport.vue';
import Button from '../Generic/Button.vue';
import api from '../../config/api';
import { toast } from 'vue-sonner';

vi.mock('../../config/api', () => ({
  default: {
    post: vi.fn().mockResolvedValue({ data: { success: true } }),
  }
}));

vi.mock('vue-sonner', () => ({
  toast: {
    error: vi.fn(),
    promise: vi.fn(),
  }
}));

describe('QuestionReport.vue', () => {
  const mockProps = {
    questionToken: 'token-123',
    question: 'Teszt kérdés?',
    answers: [{ id: 1, answer: 'Válasz A' }]
  };

  beforeEach(() => {
    vi.clearAllMocks();
  });

  it('1. Render teszt: megjeleníti a kérdést', () => {
    const wrapper = shallowMount(QuestionReport, { props: mockProps });
    expect(wrapper.text()).toContain('Teszt kérdés?');
  });

  it('2. Hibát dob üres komment esetén', async () => {
    const wrapper = shallowMount(QuestionReport, { props: mockProps });

    await wrapper.find('textarea').setValue('');

    await wrapper.findComponent(Button).trigger('click');

    expect(toast.error).toHaveBeenCalledWith(
      "Minden mező kitöltése kötelező.",
      { duration: 3000 }
    );
  });

  it('3. API hívás helyes paraméterekkel', async () => {
    const wrapper = shallowMount(QuestionReport, { props: mockProps });
    const comment = 'Teszt hiba';

    await wrapper.find('textarea').setValue(comment);
    await wrapper.findComponent(Button).trigger('click');

    expect(api.post).toHaveBeenCalledWith('/api/question-reports', {
      question_token: mockProps.questionToken,
      comment: comment
    });
  });

  it('4. Sikeres beküldés esetén isSubmitted true lesz', async () => {
    const wrapper = shallowMount(QuestionReport, { props: mockProps });

    toast.promise.mockImplementation((promise, options) => {
      options.success();
    });

    await wrapper.find('textarea').setValue('Valami hiba');
    await wrapper.findComponent(Button).trigger('click');
    expect(wrapper.findComponent({ name: 'ReportSent' }).exists()).toBe(true);
  });
});