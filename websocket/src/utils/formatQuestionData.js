export function formatQuestionData(data) {
  return {
    currentRound: data.current_round,
    subject: data.question.subject.name,
    question: data.question.question,
    answers: data.question.answers,
    questionToken: data.question_token,
  };
}
