class BalitaHandler {
  constructor() {
    this.initializeZScoreData();
    this.initializeLingkarKepalaData();
    this.initializeLilaData();
    this.lastBBData = null; // Store last BB data
  }

  initializeZScoreData() {
    this.zscoreBBU = [
      {
        umur: 0,
        median: 3.3,
        sd1: 3.0,
        sd2: 2.7,
        sd3: 2.4,
        sd1plus: 3.6,
        sd2plus: 3.9,
        sd3plus: 4.2,
      },
      {
        umur: 1,
        median: 4.5,
        sd1: 4.1,
        sd2: 3.7,
        sd3: 3.3,
        sd1plus: 4.9,
        sd2plus: 5.3,
        sd3plus: 5.7,
      },
      {
        umur: 2,
        median: 5.6,
        sd1: 5.1,
        sd2: 4.6,
        sd3: 4.1,
        sd1plus: 6.1,
        sd2plus: 6.6,
        sd3plus: 7.1,
      },
      {
        umur: 3,
        median: 6.4,
        sd1: 5.8,
        sd2: 5.2,
        sd3: 4.6,
        sd1plus: 7.0,
        sd2plus: 7.6,
        sd3plus: 8.2,
      },
      {
        umur: 4,
        median: 7.0,
        sd1: 6.4,
        sd2: 5.7,
        sd3: 5.0,
        sd1plus: 7.6,
        sd2plus: 8.2,
        sd3plus: 8.8,
      },
      {
        umur: 5,
        median: 7.5,
        sd1: 6.8,
        sd2: 6.1,
        sd3: 5.4,
        sd1plus: 8.2,
        sd2plus: 8.8,
        sd3plus: 9.4,
      },
      {
        umur: 6,
        median: 7.9,
        sd1: 7.3,
        sd2: 6.6,
        sd3: 5.9,
        sd1plus: 8.6,
        sd2plus: 9.3,
        sd3plus: 10.0,
      },
      {
        umur: 7,
        median: 8.2,
        sd1: 7.6,
        sd2: 6.9,
        sd3: 6.2,
        sd1plus: 8.9,
        sd2plus: 9.6,
        sd3plus: 10.3,
      },
      {
        umur: 8,
        median: 8.5,
        sd1: 7.9,
        sd2: 7.2,
        sd3: 6.5,
        sd1plus: 9.2,
        sd2plus: 9.9,
        sd3plus: 10.6,
      },
      {
        umur: 9,
        median: 8.8,
        sd1: 8.2,
        sd2: 7.5,
        sd3: 6.8,
        sd1plus: 9.5,
        sd2plus: 10.2,
        sd3plus: 10.9,
      },
      {
        umur: 10,
        median: 9.1,
        sd1: 8.5,
        sd2: 7.8,
        sd3: 7.1,
        sd1plus: 9.8,
        sd2plus: 10.5,
        sd3plus: 11.2,
      },
      {
        umur: 11,
        median: 9.4,
        sd1: 8.8,
        sd2: 8.1,
        sd3: 7.4,
        sd1plus: 10.1,
        sd2plus: 10.8,
        sd3plus: 11.5,
      },
      {
        umur: 12,
        median: 9.6,
        sd1: 8.6,
        sd2: 7.7,
        sd3: 6.9,
        sd1plus: 10.7,
        sd2plus: 11.8,
        sd3plus: 13.0,
      },
      {
        umur: 13,
        median: 9.8,
        sd1: 8.8,
        sd2: 7.9,
        sd3: 7.1,
        sd1plus: 10.9,
        sd2plus: 11.9,
        sd3plus: 12.9,
      },
      {
        umur: 14,
        median: 10.0,
        sd1: 9.0,
        sd2: 8.1,
        sd3: 7.3,
        sd1plus: 11.1,
        sd2plus: 12.1,
        sd3plus: 13.1,
      },
      {
        umur: 15,
        median: 10.2,
        sd1: 9.2,
        sd2: 8.3,
        sd3: 7.5,
        sd1plus: 11.3,
        sd2plus: 12.3,
        sd3plus: 13.3,
      },
      {
        umur: 16,
        median: 10.4,
        sd1: 9.4,
        sd2: 8.5,
        sd3: 7.7,
        sd1plus: 11.5,
        sd2plus: 12.5,
        sd3plus: 13.5,
      },
      {
        umur: 17,
        median: 10.6,
        sd1: 9.6,
        sd2: 8.7,
        sd3: 7.9,
        sd1plus: 11.7,
        sd2plus: 12.7,
        sd3plus: 13.7,
      },
      {
        umur: 18,
        median: 10.8,
        sd1: 9.8,
        sd2: 8.9,
        sd3: 8.1,
        sd1plus: 11.9,
        sd2plus: 12.9,
        sd3plus: 13.9,
      },
      {
        umur: 19,
        median: 11.0,
        sd1: 10.0,
        sd2: 9.1,
        sd3: 8.3,
        sd1plus: 12.1,
        sd2plus: 13.1,
        sd3plus: 14.1,
      },
      {
        umur: 20,
        median: 11.2,
        sd1: 10.2,
        sd2: 9.3,
        sd3: 8.5,
        sd1plus: 12.3,
        sd2plus: 13.3,
        sd3plus: 14.3,
      },
      {
        umur: 21,
        median: 11.4,
        sd1: 10.4,
        sd2: 9.5,
        sd3: 8.7,
        sd1plus: 12.5,
        sd2plus: 13.5,
        sd3plus: 14.5,
      },
      {
        umur: 22,
        median: 11.6,
        sd1: 10.6,
        sd2: 9.7,
        sd3: 8.9,
        sd1plus: 12.7,
        sd2plus: 13.7,
        sd3plus: 14.7,
      },
      {
        umur: 23,
        median: 11.8,
        sd1: 10.8,
        sd2: 9.9,
        sd3: 9.1,
        sd1plus: 12.9,
        sd2plus: 13.9,
        sd3plus: 14.9,
      },
      {
        umur: 24,
        median: 12.2,
        sd1: 11.1,
        sd2: 10.1,
        sd3: 9.1,
        sd1plus: 13.3,
        sd2plus: 14.4,
        sd3plus: 15.5,
      },
      {
        umur: 25,
        median: 12.4,
        sd1: 11.3,
        sd2: 10.3,
        sd3: 9.3,
        sd1plus: 13.5,
        sd2plus: 14.6,
        sd3plus: 15.7,
      },
      {
        umur: 26,
        median: 12.6,
        sd1: 11.5,
        sd2: 10.5,
        sd3: 9.5,
        sd1plus: 13.7,
        sd2plus: 14.8,
        sd3plus: 15.9,
      },
      {
        umur: 27,
        median: 12.8,
        sd1: 11.7,
        sd2: 10.7,
        sd3: 9.7,
        sd1plus: 13.9,
        sd2plus: 15.0,
        sd3plus: 16.1,
      },
      {
        umur: 28,
        median: 13.0,
        sd1: 11.9,
        sd2: 10.9,
        sd3: 9.9,
        sd1plus: 14.1,
        sd2plus: 15.2,
        sd3plus: 16.3,
      },
      {
        umur: 29,
        median: 13.2,
        sd1: 12.1,
        sd2: 11.1,
        sd3: 10.1,
        sd1plus: 14.3,
        sd2plus: 15.4,
        sd3plus: 16.5,
      },
      {
        umur: 30,
        median: 13.4,
        sd1: 12.3,
        sd2: 11.3,
        sd3: 10.3,
        sd1plus: 14.5,
        sd2plus: 15.6,
        sd3plus: 16.7,
      },
      {
        umur: 31,
        median: 13.6,
        sd1: 12.5,
        sd2: 11.5,
        sd3: 10.5,
        sd1plus: 14.7,
        sd2plus: 15.8,
        sd3plus: 16.9,
      },
      {
        umur: 32,
        median: 13.8,
        sd1: 12.7,
        sd2: 11.7,
        sd3: 10.7,
        sd1plus: 14.9,
        sd2plus: 16.0,
        sd3plus: 17.1,
      },
      {
        umur: 33,
        median: 14.0,
        sd1: 12.9,
        sd2: 11.9,
        sd3: 10.9,
        sd1plus: 15.1,
        sd2plus: 16.2,
        sd3plus: 17.3,
      },
      {
        umur: 34,
        median: 14.2,
        sd1: 13.1,
        sd2: 12.1,
        sd3: 11.1,
        sd1plus: 15.3,
        sd2plus: 16.4,
        sd3plus: 17.5,
      },
      {
        umur: 35,
        median: 14.4,
        sd1: 13.3,
        sd2: 12.3,
        sd3: 11.3,
        sd1plus: 15.5,
        sd2plus: 16.6,
        sd3plus: 17.7,
      },
      {
        umur: 36,
        median: 13.9,
        sd1: 12.7,
        sd2: 11.5,
        sd3: 10.3,
        sd1plus: 15.1,
        sd2plus: 16.3,
        sd3plus: 17.5,
      },
      {
        umur: 37,
        median: 14.1,
        sd1: 12.9,
        sd2: 11.7,
        sd3: 10.5,
        sd1plus: 15.3,
        sd2plus: 16.5,
        sd3plus: 17.7,
      },
      {
        umur: 38,
        median: 14.3,
        sd1: 13.1,
        sd2: 11.9,
        sd3: 10.7,
        sd1plus: 15.5,
        sd2plus: 16.7,
        sd3plus: 17.9,
      },
      {
        umur: 39,
        median: 14.5,
        sd1: 13.3,
        sd2: 12.1,
        sd3: 11.1,
        sd1plus: 15.7,
        sd2plus: 16.9,
        sd3plus: 18.1,
      },
      {
        umur: 40,
        median: 14.7,
        sd1: 13.5,
        sd2: 12.3,
        sd3: 11.3,
        sd1plus: 15.9,
        sd2plus: 17.1,
        sd3plus: 18.3,
      },
      {
        umur: 41,
        median: 14.9,
        sd1: 13.7,
        sd2: 12.5,
        sd3: 11.5,
        sd1plus: 16.1,
        sd2plus: 17.3,
        sd3plus: 18.5,
      },
      {
        umur: 42,
        median: 15.1,
        sd1: 13.9,
        sd2: 12.7,
        sd3: 11.7,
        sd1plus: 16.3,
        sd2plus: 17.5,
        sd3plus: 18.7,
      },
      {
        umur: 43,
        median: 15.3,
        sd1: 14.1,
        sd2: 12.9,
        sd3: 11.9,
        sd1plus: 16.5,
        sd2plus: 17.7,
        sd3plus: 18.9,
      },
      {
        umur: 44,
        median: 15.5,
        sd1: 14.3,
        sd2: 13.1,
        sd3: 12.0,
        sd1plus: 16.7,
        sd2plus: 17.9,
        sd3plus: 19.2,
      },
      {
        umur: 45,
        median: 15.7,
        sd1: 14.5,
        sd2: 13.3,
        sd3: 12.2,
        sd1plus: 16.9,
        sd2plus: 18.1,
        sd3plus: 19.3,
      },
      {
        umur: 46,
        median: 15.9,
        sd1: 14.7,
        sd2: 13.5,
        sd3: 12.4,
        sd1plus: 17.1,
        sd2plus: 18.3,
        sd3plus: 19.5,
      },
      {
        umur: 47,
        median: 16.1,
        sd1: 14.9,
        sd2: 13.7,
        sd3: 12.6,
        sd1plus: 17.3,
        sd2plus: 18.5,
        sd3plus: 19.7,
      },
      {
        umur: 48,
        median: 15.3,
        sd1: 14.0,
        sd2: 12.7,
        sd3: 11.4,
        sd1plus: 16.6,
        sd2plus: 17.9,
        sd3plus: 19.2,
      },
      {
        umur: 49,
        median: 15.5,
        sd1: 14.2,
        sd2: 12.9,
        sd3: 11.6,
        sd1plus: 16.8,
        sd2plus: 18.1,
        sd3plus: 19.4,
      },
      {
        umur: 50,
        median: 15.7,
        sd1: 14.4,
        sd2: 13.1,
        sd3: 12.0,
        sd1plus: 17.0,
        sd2plus: 18.3,
        sd3plus: 19.6,
      },
      {
        umur: 51,
        median: 15.9,
        sd1: 14.6,
        sd2: 13.3,
        sd3: 12.2,
        sd1plus: 17.2,
        sd2plus: 18.5,
        sd3plus: 19.8,
      },
      {
        umur: 52,
        median: 16.1,
        sd1: 14.8,
        sd2: 13.5,
        sd3: 12.4,
        sd1plus: 17.4,
        sd2plus: 18.7,
        sd3plus: 20.0,
      },
      {
        umur: 53,
        median: 16.3,
        sd1: 15.0,
        sd2: 13.7,
        sd3: 12.6,
        sd1plus: 17.6,
        sd2plus: 18.9,
        sd3plus: 20.2,
      },
      {
        umur: 54,
        median: 16.5,
        sd1: 15.2,
        sd2: 13.9,
        sd3: 12.8,
        sd1plus: 17.8,
        sd2plus: 19.1,
        sd3plus: 20.4,
      },
      {
        umur: 55,
        median: 16.7,
        sd1: 15.4,
        sd2: 14.1,
        sd3: 13.0,
        sd1plus: 18.0,
        sd2plus: 19.4,
        sd3plus: 20.8,
      },
      {
        umur: 56,
        median: 16.9,
        sd1: 15.6,
        sd2: 14.3,
        sd3: 13.2,
        sd1plus: 18.2,
        sd2plus: 19.6,
        sd3plus: 21.0,
      },
      {
        umur: 57,
        median: 17.1,
        sd1: 15.8,
        sd2: 14.5,
        sd3: 13.4,
        sd1plus: 18.4,
        sd2plus: 19.8,
        sd3plus: 21.2,
      },
      {
        umur: 58,
        median: 17.3,
        sd1: 16.0,
        sd2: 14.7,
        sd3: 13.6,
        sd1plus: 18.6,
        sd2plus: 20.0,
        sd3plus: 21.4,
      },
      {
        umur: 59,
        median: 17.5,
        sd1: 16.2,
        sd2: 14.9,
        sd3: 13.8,
        sd1plus: 18.8,
        sd2plus: 20.2,
        sd3plus: 21.6,
      },
      {
        umur: 60,
        median: 16.7,
        sd1: 15.3,
        sd2: 13.9,
        sd3: 12.5,
        sd1plus: 18.0,
        sd2plus: 19.4,
        sd3plus: 20.8,
      },
    ];

    this.zscoreTBU = [
      {
        umur: 0,
        median: 49.9,
        sd1: 48.0,
        sd2: 46.1,
        sd3: 44.2,
        sd1plus: 51.8,
        sd2plus: 53.7,
        sd3plus: 55.6,
      },
      {
        umur: 1,
        median: 54.7,
        sd1: 52.8,
        sd2: 50.9,
        sd3: 49.0,
        sd1plus: 56.6,
        sd2plus: 58.5,
        sd3plus: 60.4,
      },
      {
        umur: 2,
        median: 58.4,
        sd1: 56.4,
        sd2: 54.4,
        sd3: 52.4,
        sd1plus: 60.4,
        sd2plus: 62.4,
        sd3plus: 64.4,
      },
      {
        umur: 3,
        median: 61.4,
        sd1: 59.4,
        sd2: 57.4,
        sd3: 55.4,
        sd1plus: 63.4,
        sd2plus: 65.4,
        sd3plus: 67.4,
      },
      {
        umur: 4,
        median: 63.9,
        sd1: 61.8,
        sd2: 59.7,
        sd3: 57.6,
        sd1plus: 66.0,
        sd2plus: 68.1,
        sd3plus: 70.2,
      },
      {
        umur: 5,
        median: 65.9,
        sd1: 63.7,
        sd2: 61.5,
        sd3: 59.3,
        sd1plus: 68.1,
        sd2plus: 70.3,
        sd3plus: 72.5,
      },
      {
        umur: 6,
        median: 67.6,
        sd1: 65.4,
        sd2: 63.2,
        sd3: 61.0,
        sd1plus: 70.0,
        sd2plus: 72.2,
        sd3plus: 74.4,
      },
      {
        umur: 7,
        median: 69.1,
        sd1: 66.9,
        sd2: 64.7,
        sd3: 62.5,
        sd1plus: 71.5,
        sd2plus: 73.7,
        sd3plus: 75.9,
      },
      {
        umur: 8,
        median: 70.4,
        sd1: 68.2,
        sd2: 66.0,
        sd3: 63.8,
        sd1plus: 72.8,
        sd2plus: 75.0,
        sd3plus: 77.2,
      },
      {
        umur: 9,
        median: 71.6,
        sd1: 69.4,
        sd2: 67.2,
        sd3: 65.0,
        sd1plus: 74.0,
        sd2plus: 76.2,
        sd3plus: 78.4,
      },
      {
        umur: 10,
        median: 72.7,
        sd1: 70.5,
        sd2: 68.3,
        sd3: 66.1,
        sd1plus: 75.2,
        sd2plus: 77.4,
        sd3plus: 79.6,
      },
      {
        umur: 11,
        median: 73.8,
        sd1: 71.6,
        sd2: 69.4,
        sd3: 67.2,
        sd1plus: 76.4,
        sd2plus: 78.6,
        sd3plus: 80.8,
      },
      {
        umur: 12,
        median: 75.7,
        sd1: 73.3,
        sd2: 70.8,
        sd3: 68.4,
        sd1plus: 78.1,
        sd2plus: 80.5,
        sd3plus: 82.9,
      },
      {
        umur: 13,
        median: 77.5,
        sd1: 75.1,
        sd2: 72.7,
        sd3: 70.3,
        sd1plus: 79.9,
        sd2plus: 82.3,
        sd3plus: 84.7,
      },
      {
        umur: 14,
        median: 79.2,
        sd1: 76.8,
        sd2: 74.4,
        sd3: 72.0,
        sd1plus: 81.6,
        sd2plus: 84.0,
        sd3plus: 86.4,
      },
      {
        umur: 15,
        median: 80.8,
        sd1: 78.4,
        sd2: 76.0,
        sd3: 73.6,
        sd1plus: 83.2,
        sd2plus: 85.6,
        sd3plus: 88.0,
      },
      {
        umur: 16,
        median: 82.3,
        sd1: 79.9,
        sd2: 77.5,
        sd3: 75.1,
        sd1plus: 84.7,
        sd2plus: 87.1,
        sd3plus: 89.5,
      },
      {
        umur: 17,
        median: 83.8,
        sd1: 81.4,
        sd2: 79.0,
        sd3: 76.6,
        sd1plus: 86.2,
        sd2plus: 88.6,
        sd3plus: 91.0,
      },
      {
        umur: 18,
        median: 85.2,
        sd1: 82.8,
        sd2: 80.4,
        sd3: 78.0,
        sd1plus: 87.6,
        sd2plus: 90.0,
        sd3plus: 92.4,
      },
      {
        umur: 19,
        median: 86.5,
        sd1: 84.1,
        sd2: 81.7,
        sd3: 79.3,
        sd1plus: 88.9,
        sd2plus: 91.3,
        sd3plus: 93.7,
      },
      {
        umur: 20,
        median: 87.7,
        sd1: 85.3,
        sd2: 83.0,
        sd3: 80.6,
        sd1plus: 90.1,
        sd2plus: 92.5,
        sd3plus: 94.9,
      },
      {
        umur: 21,
        median: 88.8,
        sd1: 86.4,
        sd2: 84.1,
        sd3: 81.7,
        sd1plus: 91.2,
        sd2plus: 93.6,
        sd3plus: 96.0,
      },
      {
        umur: 22,
        median: 89.9,
        sd1: 87.5,
        sd2: 85.2,
        sd3: 82.8,
        sd1plus: 92.3,
        sd2plus: 94.7,
        sd3plus: 97.1,
      },
      {
        umur: 23,
        median: 91.0,
        sd1: 88.6,
        sd2: 86.3,
        sd3: 83.9,
        sd1plus: 93.4,
        sd2plus: 95.8,
        sd3plus: 98.2,
      },
      {
        umur: 24,
        median: 92.1,
        sd1: 89.7,
        sd2: 87.4,
        sd3: 85.0,
        sd1plus: 94.5,
        sd2plus: 96.9,
        sd3plus: 99.3,
      },
      {
        umur: 25,
        median: 93.2,
        sd1: 90.8,
        sd2: 88.5,
        sd3: 86.1,
        sd1plus: 95.6,
        sd2plus: 98.0,
        sd3plus: 100.4,
      },
      {
        umur: 26,
        median: 94.2,
        sd1: 91.8,
        sd2: 89.5,
        sd3: 87.2,
        sd1plus: 96.6,
        sd2plus: 99.0,
        sd3plus: 101.4,
      },
      {
        umur: 27,
        median: 95.2,
        sd1: 92.8,
        sd2: 90.5,
        sd3: 88.2,
        sd1plus: 97.6,
        sd2plus: 100.0,
        sd3plus: 102.4,
      },
      {
        umur: 28,
        median: 96.1,
        sd1: 93.7,
        sd2: 91.4,
        sd3: 89.2,
        sd1plus: 98.6,
        sd2plus: 101.0,
        sd3plus: 103.4,
      },
      {
        umur: 29,
        median: 97.0,
        sd1: 94.6,
        sd2: 92.3,
        sd3: 90.0,
        sd1plus: 99.6,
        sd2plus: 102.0,
        sd3plus: 104.4,
      },
      {
        umur: 30,
        median: 97.9,
        sd1: 95.5,
        sd2: 93.2,
        sd3: 90.8,
        sd1plus: 100.6,
        sd2plus: 103.0,
        sd3plus: 105.4,
      },
      {
        umur: 31,
        median: 98.7,
        sd1: 96.3,
        sd2: 94.1,
        sd3: 91.8,
        sd1plus: 101.5,
        sd2plus: 104.0,
        sd3plus: 106.5,
      },
      {
        umur: 32,
        median: 99.5,
        sd1: 97.1,
        sd2: 94.9,
        sd3: 92.7,
        sd1plus: 102.4,
        sd2plus: 105.0,
        sd3plus: 107.6,
      },
      {
        umur: 33,
        median: 100.3,
        sd1: 97.9,
        sd2: 95.7,
        sd3: 93.5,
        sd1plus: 103.2,
        sd2plus: 105.8,
        sd3plus: 108.4,
      },
      {
        umur: 34,
        median: 101.1,
        sd1: 98.7,
        sd2: 96.5,
        sd3: 94.3,
        sd1plus: 104.1,
        sd2plus: 106.7,
        sd3plus: 109.3,
      },
      {
        umur: 35,
        median: 101.9,
        sd1: 99.5,
        sd2: 97.3,
        sd3: 95.1,
        sd1plus: 105.0,
        sd2plus: 107.6,
        sd3plus: 110.2,
      },
      {
        umur: 36,
        median: 102.7,
        sd1: 100.3,
        sd2: 98.1,
        sd3: 95.9,
        sd1plus: 105.9,
        sd2plus: 108.5,
        sd3plus: 111.1,
      },
      {
        umur: 37,
        median: 103.5,
        sd1: 101.1,
        sd2: 98.9,
        sd3: 96.7,
        sd1plus: 106.8,
        sd2plus: 109.4,
        sd3plus: 112.0,
      },
      {
        umur: 38,
        median: 104.3,
        sd1: 101.9,
        sd2: 99.7,
        sd3: 97.5,
        sd1plus: 107.7,
        sd2plus: 110.3,
        sd3plus: 113.0,
      },
      {
        umur: 39,
        median: 105.1,
        sd1: 102.7,
        sd2: 100.5,
        sd3: 98.3,
        sd1plus: 108.5,
        sd2plus: 111.1,
        sd3plus: 113.7,
      },
      {
        umur: 40,
        median: 105.9,
        sd1: 103.5,
        sd2: 101.3,
        sd3: 99.1,
        sd1plus: 109.4,
        sd2plus: 112.0,
        sd3plus: 114.6,
      },
      {
        umur: 41,
        median: 106.7,
        sd1: 104.3,
        sd2: 102.1,
        sd3: 100.0,
        sd1plus: 110.2,
        sd2plus: 112.8,
        sd3plus: 115.4,
      },
      {
        umur: 42,
        median: 107.5,
        sd1: 105.1,
        sd2: 102.9,
        sd3: 100.8,
        sd1plus: 111.1,
        sd2plus: 113.7,
        sd3plus: 116.3,
      },
      {
        umur: 43,
        median: 108.3,
        sd1: 105.9,
        sd2: 103.7,
        sd3: 101.6,
        sd1plus: 112.0,
        sd2plus: 114.6,
        sd3plus: 117.2,
      },
      {
        umur: 44,
        median: 109.1,
        sd1: 106.7,
        sd2: 104.5,
        sd3: 102.4,
        sd1plus: 112.9,
        sd2plus: 115.5,
        sd3plus: 118.1,
      },
      {
        umur: 45,
        median: 109.9,
        sd1: 107.5,
        sd2: 105.3,
        sd3: 103.2,
        sd1plus: 113.8,
        sd2plus: 116.4,
        sd3plus: 119.0,
      },
      {
        umur: 46,
        median: 110.7,
        sd1: 108.3,
        sd2: 106.1,
        sd3: 104.0,
        sd1plus: 114.6,
        sd2plus: 117.2,
        sd3plus: 119.8,
      },
      {
        umur: 47,
        median: 111.5,
        sd1: 109.1,
        sd2: 106.9,
        sd3: 104.8,
        sd1plus: 115.5,
        sd2plus: 118.1,
        sd3plus: 120.7,
      },
      {
        umur: 48,
        median: 112.3,
        sd1: 109.9,
        sd2: 107.7,
        sd3: 105.6,
        sd1plus: 116.4,
        sd2plus: 119.0,
        sd3plus: 121.6,
      },
      {
        umur: 49,
        median: 113.1,
        sd1: 110.7,
        sd2: 108.5,
        sd3: 106.4,
        sd1plus: 117.3,
        sd2plus: 120.0,
        sd3plus: 122.7,
      },
      {
        umur: 50,
        median: 113.9,
        sd1: 111.5,
        sd2: 109.3,
        sd3: 107.2,
        sd1plus: 118.2,
        sd2plus: 120.9,
        sd3plus: 123.6,
      },
      {
        umur: 51,
        median: 114.7,
        sd1: 112.3,
        sd2: 110.1,
        sd3: 108.0,
        sd1plus: 119.1,
        sd2plus: 121.8,
        sd3plus: 124.5,
      },
      {
        umur: 52,
        median: 115.5,
        sd1: 113.1,
        sd2: 110.9,
        sd3: 108.8,
        sd1plus: 120.0,
        sd2plus: 122.7,
        sd3plus: 125.4,
      },
      {
        umur: 53,
        median: 116.3,
        sd1: 113.9,
        sd2: 111.7,
        sd3: 109.6,
        sd1plus: 120.9,
        sd2plus: 123.6,
        sd3plus: 126.3,
      },
      {
        umur: 54,
        median: 117.1,
        sd1: 114.7,
        sd2: 112.5,
        sd3: 110.4,
        sd1plus: 121.8,
        sd2plus: 124.5,
        sd3plus: 127.2,
      },
      {
        umur: 55,
        median: 117.9,
        sd1: 115.5,
        sd2: 113.3,
        sd3: 111.2,
        sd1plus: 122.7,
        sd2plus: 125.4,
        sd3plus: 128.1,
      },
      {
        umur: 56,
        median: 118.7,
        sd1: 116.3,
        sd2: 114.1,
        sd3: 112.0,
        sd1plus: 123.6,
        sd2plus: 126.3,
        sd3plus: 129.0,
      },
      {
        umur: 57,
        median: 119.5,
        sd1: 117.1,
        sd2: 114.9,
        sd3: 112.8,
        sd1plus: 124.5,
        sd2plus: 127.2,
        sd3plus: 130.0,
      },
      {
        umur: 58,
        median: 120.3,
        sd1: 117.9,
        sd2: 115.7,
        sd3: 113.6,
        sd1plus: 125.4,
        sd2plus: 128.1,
        sd3plus: 130.9,
      },
      {
        umur: 59,
        median: 121.1,
        sd1: 118.7,
        sd2: 116.5,
        sd3: 114.4,
        sd1plus: 126.3,
        sd2plus: 129.0,
        sd3plus: 131.8,
      },
      {
        umur: 60,
        median: 122.0,
        sd1: 119.5,
        sd2: 117.3,
        sd3: 115.2,
        sd1plus: 127.2,
        sd2plus: 130.0,
        sd3plus: 132.8,
      },
    ];

    this.zscoreBBTB = [
      {
        tb: 65,
        median: 7.1,
        sd1: 6.5,
        sd2: 5.9,
        sd3: 5.3,
        sd1plus: 7.8,
        sd2plus: 8.5,
        sd3plus: 9.2,
      },
      {
        tb: 66,
        median: 7.3,
        sd1: 6.7,
        sd2: 6.1,
        sd3: 5.5,
        sd1plus: 8.0,
        sd2plus: 8.7,
        sd3plus: 9.4,
      },
      {
        tb: 67,
        median: 7.5,
        sd1: 6.9,
        sd2: 6.3,
        sd3: 5.7,
        sd1plus: 8.2,
        sd2plus: 8.9,
        sd3plus: 9.6,
      },
      {
        tb: 68,
        median: 7.7,
        sd1: 7.1,
        sd2: 6.5,
        sd3: 5.9,
        sd1plus: 8.4,
        sd2plus: 9.1,
        sd3plus: 9.8,
      },
      {
        tb: 69,
        median: 7.9,
        sd1: 7.3,
        sd2: 6.7,
        sd3: 6.1,
        sd1plus: 8.6,
        sd2plus: 9.3,
        sd3plus: 10.0,
      },
      {
        tb: 70,
        median: 8.2,
        sd1: 7.5,
        sd2: 6.8,
        sd3: 6.1,
        sd1plus: 8.9,
        sd2plus: 9.6,
        sd3plus: 10.3,
      },
      {
        tb: 71,
        median: 8.4,
        sd1: 7.7,
        sd2: 7.0,
        sd3: 6.3,
        sd1plus: 9.1,
        sd2plus: 9.8,
        sd3plus: 10.5,
      },
      {
        tb: 72,
        median: 8.6,
        sd1: 7.9,
        sd2: 7.2,
        sd3: 6.5,
        sd1plus: 9.3,
        sd2plus: 10.0,
        sd3plus: 10.7,
      },
      {
        tb: 73,
        median: 8.8,
        sd1: 8.1,
        sd2: 7.4,
        sd3: 6.7,
        sd1plus: 9.5,
        sd2plus: 10.2,
        sd3plus: 10.9,
      },
      {
        tb: 74,
        median: 9.0,
        sd1: 8.3,
        sd2: 7.6,
        sd3: 6.9,
        sd1plus: 9.7,
        sd2plus: 10.4,
        sd3plus: 11.1,
      },
      {
        tb: 75,
        median: 9.2,
        sd1: 8.4,
        sd2: 7.7,
        sd3: 7.0,
        sd1plus: 10.0,
        sd2plus: 10.9,
        sd3plus: 11.9,
      },
      {
        tb: 76,
        median: 9.4,
        sd1: 8.6,
        sd2: 7.9,
        sd3: 7.2,
        sd1plus: 10.2,
        sd2plus: 11.1,
        sd3plus: 12.1,
      },
      {
        tb: 77,
        median: 9.6,
        sd1: 8.8,
        sd2: 8.1,
        sd3: 7.4,
        sd1plus: 10.4,
        sd2plus: 11.3,
        sd3plus: 12.3,
      },
      {
        tb: 78,
        median: 9.8,
        sd1: 9.0,
        sd2: 8.3,
        sd3: 7.6,
        sd1plus: 10.6,
        sd2plus: 11.5,
        sd3plus: 12.5,
      },
      {
        tb: 79,
        median: 10.0,
        sd1: 9.2,
        sd2: 8.5,
        sd3: 7.8,
        sd1plus: 10.8,
        sd2plus: 11.7,
        sd3plus: 12.7,
      },
      {
        tb: 80,
        median: 10.2,
        sd1: 9.4,
        sd2: 8.7,
        sd3: 8.0,
        sd1plus: 11.0,
        sd2plus: 11.9,
        sd3plus: 12.9,
      },
      {
        tb: 81,
        median: 10.4,
        sd1: 9.6,
        sd2: 8.9,
        sd3: 8.2,
        sd1plus: 11.2,
        sd2plus: 12.1,
        sd3plus: 13.1,
      },
      {
        tb: 82,
        median: 10.6,
        sd1: 9.8,
        sd2: 9.1,
        sd3: 8.4,
        sd1plus: 11.4,
        sd2plus: 12.3,
        sd3plus: 13.3,
      },
      {
        tb: 83,
        median: 10.8,
        sd1: 10.0,
        sd2: 9.3,
        sd3: 8.6,
        sd1plus: 11.6,
        sd2plus: 12.5,
        sd3plus: 13.5,
      },
      {
        tb: 84,
        median: 11.0,
        sd1: 10.2,
        sd2: 9.5,
        sd3: 8.8,
        sd1plus: 11.8,
        sd2plus: 12.7,
        sd3plus: 13.7,
      },
      {
        tb: 85,
        median: 11.2,
        sd1: 10.4,
        sd2: 9.7,
        sd3: 9.0,
        sd1plus: 12.0,
        sd2plus: 12.9,
        sd3plus: 13.9,
      },
      {
        tb: 86,
        median: 11.4,
        sd1: 10.6,
        sd2: 9.9,
        sd3: 9.2,
        sd1plus: 12.2,
        sd2plus: 13.1,
        sd3plus: 14.1,
      },
      {
        tb: 87,
        median: 11.6,
        sd1: 10.8,
        sd2: 10.1,
        sd3: 9.4,
        sd1plus: 12.4,
        sd2plus: 13.3,
        sd3plus: 14.3,
      },
      {
        tb: 88,
        median: 11.8,
        sd1: 11.0,
        sd2: 10.3,
        sd3: 9.6,
        sd1plus: 12.6,
        sd2plus: 13.5,
        sd3plus: 14.5,
      },
      {
        tb: 89,
        median: 12.0,
        sd1: 11.2,
        sd2: 10.5,
        sd3: 9.8,
        sd1plus: 12.8,
        sd2plus: 13.7,
        sd3plus: 14.7,
      },
      {
        tb: 90,
        median: 12.2,
        sd1: 11.4,
        sd2: 10.7,
        sd3: 10.0,
        sd1plus: 13.0,
        sd2plus: 13.9,
        sd3plus: 14.9,
      },
      {
        tb: 91,
        median: 12.4,
        sd1: 11.6,
        sd2: 10.9,
        sd3: 10.2,
        sd1plus: 13.2,
        sd2plus: 14.1,
        sd3plus: 15.1,
      },
      {
        tb: 92,
        median: 12.6,
        sd1: 11.8,
        sd2: 11.1,
        sd3: 10.4,
        sd1plus: 13.4,
        sd2plus: 14.3,
        sd3plus: 15.3,
      },
      {
        tb: 93,
        median: 12.8,
        sd1: 12.0,
        sd2: 11.3,
        sd3: 10.6,
        sd1plus: 13.6,
        sd2plus: 14.5,
        sd3plus: 15.5,
      },
      {
        tb: 94,
        median: 13.0,
        sd1: 12.2,
        sd2: 11.5,
        sd3: 10.8,
        sd1plus: 13.8,
        sd2plus: 14.7,
        sd3plus: 15.7,
      },
      {
        tb: 95,
        median: 13.2,
        sd1: 12.4,
        sd2: 11.7,
        sd3: 11.0,
        sd1plus: 14.0,
        sd2plus: 14.9,
        sd3plus: 15.9,
      },
      {
        tb: 96,
        median: 13.4,
        sd1: 12.6,
        sd2: 11.9,
        sd3: 11.2,
        sd1plus: 14.2,
        sd2plus: 15.1,
        sd3plus: 16.1,
      },
      {
        tb: 97,
        median: 13.6,
        sd1: 12.8,
        sd2: 12.1,
        sd3: 11.4,
        sd1plus: 14.4,
        sd2plus: 15.3,
        sd3plus: 16.3,
      },
      {
        tb: 98,
        median: 13.8,
        sd1: 13.0,
        sd2: 12.3,
        sd3: 11.6,
        sd1plus: 14.6,
        sd2plus: 15.5,
        sd3plus: 16.5,
      },
      {
        tb: 99,
        median: 14.0,
        sd1: 13.2,
        sd2: 12.5,
        sd3: 11.8,
        sd1plus: 14.8,
        sd2plus: 15.7,
        sd3plus: 16.7,
      },
      {
        tb: 100,
        median: 14.2,
        sd1: 13.4,
        sd2: 12.7,
        sd3: 12.0,
        sd1plus: 15.0,
        sd2plus: 15.9,
        sd3plus: 16.9,
      },
      {
        tb: 101,
        median: 14.4,
        sd1: 13.6,
        sd2: 12.9,
        sd3: 12.2,
        sd1plus: 15.2,
        sd2plus: 16.1,
        sd3plus: 17.1,
      },
      {
        tb: 102,
        median: 14.6,
        sd1: 13.8,
        sd2: 13.1,
        sd3: 12.4,
        sd1plus: 15.4,
        sd2plus: 16.3,
        sd3plus: 17.3,
      },
      {
        tb: 103,
        median: 14.8,
        sd1: 14.0,
        sd2: 13.3,
        sd3: 12.6,
        sd1plus: 15.6,
        sd2plus: 16.5,
        sd3plus: 17.5,
      },
      {
        tb: 104,
        median: 15.0,
        sd1: 14.2,
        sd2: 13.5,
        sd3: 12.8,
        sd1plus: 15.8,
        sd2plus: 16.7,
        sd3plus: 17.7,
      },
      {
        tb: 105,
        median: 15.2,
        sd1: 14.4,
        sd2: 13.7,
        sd3: 13.0,
        sd1plus: 16.0,
        sd2plus: 16.9,
        sd3plus: 17.9,
      },
      {
        tb: 106,
        median: 15.4,
        sd1: 14.6,
        sd2: 13.9,
        sd3: 13.2,
        sd1plus: 16.2,
        sd2plus: 17.1,
        sd3plus: 18.1,
      },
      {
        tb: 107,
        median: 15.6,
        sd1: 14.8,
        sd2: 14.1,
        sd3: 13.4,
        sd1plus: 16.4,
        sd2plus: 17.3,
        sd3plus: 18.3,
      },
      {
        tb: 108,
        median: 15.8,
        sd1: 15.0,
        sd2: 14.3,
        sd3: 13.6,
        sd1plus: 16.6,
        sd2plus: 17.5,
        sd3plus: 18.5,
      },
      {
        tb: 109,
        median: 16.0,
        sd1: 15.2,
        sd2: 14.5,
        sd3: 13.8,
        sd1plus: 16.8,
        sd2plus: 17.7,
        sd3plus: 18.7,
      },
      {
        tb: 110,
        median: 16.2,
        sd1: 15.4,
        sd2: 14.7,
        sd3: 14.0,
        sd1plus: 17.0,
        sd2plus: 17.9,
        sd3plus: 18.9,
      },
      // Extended data untuk TB 111-120
      {
        tb: 111,
        median: 16.4,
        sd1: 15.6,
        sd2: 14.9,
        sd3: 14.2,
        sd1plus: 17.2,
        sd2plus: 18.1,
        sd3plus: 19.1,
      },
      {
        tb: 112,
        median: 16.6,
        sd1: 15.8,
        sd2: 15.1,
        sd3: 14.4,
        sd1plus: 17.4,
        sd2plus: 18.3,
        sd3plus: 19.3,
      },
      {
        tb: 113,
        median: 16.8,
        sd1: 16.0,
        sd2: 15.3,
        sd3: 14.6,
        sd1plus: 17.6,
        sd2plus: 18.5,
        sd3plus: 19.5,
      },
      {
        tb: 114,
        median: 17.0,
        sd1: 16.2,
        sd2: 15.5,
        sd3: 14.8,
        sd1plus: 17.8,
        sd2plus: 18.7,
        sd3plus: 19.7,
      },
      {
        tb: 115,
        median: 17.2,
        sd1: 16.4,
        sd2: 15.7,
        sd3: 15.0,
        sd1plus: 18.0,
        sd2plus: 18.9,
        sd3plus: 19.9,
      },
      {
        tb: 116,
        median: 17.4,
        sd1: 16.6,
        sd2: 15.9,
        sd3: 15.2,
        sd1plus: 18.2,
        sd2plus: 19.1,
        sd3plus: 20.1,
      },
      {
        tb: 117,
        median: 17.6,
        sd1: 16.8,
        sd2: 16.1,
        sd3: 15.4,
        sd1plus: 18.4,
        sd2plus: 19.3,
        sd3plus: 20.3,
      },
      {
        tb: 118,
        median: 17.8,
        sd1: 17.0,
        sd2: 16.3,
        sd3: 15.6,
        sd1plus: 18.6,
        sd2plus: 19.5,
        sd3plus: 20.5,
      },
      {
        tb: 119,
        median: 18.0,
        sd1: 17.2,
        sd2: 16.5,
        sd3: 15.8,
        sd1plus: 18.8,
        sd2plus: 19.7,
        sd3plus: 20.7,
      },
      {
        tb: 120,
        median: 18.2,
        sd1: 17.4,
        sd2: 16.7,
        sd3: 16.0,
        sd1plus: 19.0,
        sd2plus: 19.9,
        sd3plus: 20.9,
      },
    ];
  }
  initializeLilaData() {
    // Standar LILA untuk umur 6-60 bulan (berdasarkan tabel yang dikirim)
    this.standarLila = {
      // Gizi buruk: < 11.5 cm (SAM - Merah)
      // Gizi kurang: 11.5-12.4 cm (MAM - Kuning)
      // Gizi baik: ≥ 12.5 cm (Normal - Hijau)
      gizi_buruk_threshold: 11.5,
      gizi_kurang_min: 11.5,
      gizi_kurang_max: 12.4,
      gizi_baik_min: 12.5,
    };
  }

  // ✅ TAMBAH METHOD VALIDASI UMUR LILA
  validateUmurLila() {
    const umurField = document.getElementById('umur');
    const lilaField = document.getElementById('lila');
    const lilaStatus = document.getElementById('lila-status');
    const kesimpulanLilaField = document.getElementById('kesimpulan_lila');
    const kesimpulanLilaInfo = document.getElementById('kesimpulan-lila-info');

    if (!umurField || !lilaField || !lilaStatus) return;

    const umur = parseInt(umurField.value) || 0;

    if (umur < 6) {
      // DISABLE FIELD JIKA BELUM 6 BULAN
      lilaField.disabled = true;
      lilaField.value = '';
      lilaField.style.backgroundColor = '#f8f9fa';
      lilaField.style.cursor = 'not-allowed';
      lilaStatus.innerHTML =
        '<i class="bi bi-exclamation-triangle text-warning me-1"></i>Belum berumur 6 bulan, LILA tidak diukur';

      if (kesimpulanLilaField) {
        kesimpulanLilaField.value = 'Belum berumur 6 bulan';
        kesimpulanLilaField.className = 'form-control bg-light';
      }
      if (kesimpulanLilaInfo) {
        kesimpulanLilaInfo.textContent = 'LILA hanya diukur untuk balita usia 6-60 bulan';
      }
    } else if (umur >= 6 && umur <= 60) {
      // ENABLE FIELD JIKA SUDAH 6 BULAN
      lilaField.disabled = false;
      lilaField.style.backgroundColor = '';
      lilaField.style.cursor = '';
      lilaStatus.innerHTML =
        '<i class="bi bi-check-circle text-success me-1"></i>Dapat diukur LILA (usia 6-60 bulan)';

      if (kesimpulanLilaInfo) {
        kesimpulanLilaInfo.textContent = 'Kesimpulan otomatis berdasarkan input LILA';
      }

      // EVALUASI LILA JIKA SUDAH ADA VALUE
      this.evaluasiLila();
    } else {
      // UMUR > 60 BULAN
      lilaField.disabled = true;
      lilaField.value = '';
      lilaField.style.backgroundColor = '#f8f9fa';
      lilaField.style.cursor = 'not-allowed';
      lilaStatus.innerHTML =
        '<i class="bi bi-info-circle text-info me-1"></i>Usia > 60 bulan, LILA tidak diperlukan';

      if (kesimpulanLilaField) {
        kesimpulanLilaField.value = 'Usia > 60 bulan';
        kesimpulanLilaField.className = 'form-control bg-light';
      }
    }
  }

  // ✅ TAMBAH METHOD EVALUASI LILA
  evaluasiLila() {
    const umurField = document.getElementById('umur');
    const lilaField = document.getElementById('lila');
    const kesimpulanField = document.getElementById('kesimpulan_lila');

    if (!umurField || !lilaField || !kesimpulanField) return;

    const umur = parseInt(umurField.value) || 0;
    const lila = parseFloat(lilaField.value) || 0;

    // SKIP JIKA UMUR TIDAK VALID ATAU LILA KOSONG
    if (umur < 6 || umur > 60 || !lila) {
      if (umur >= 6 && umur <= 60 && !lila) {
        kesimpulanField.value = '';
        kesimpulanField.className = 'form-control bg-light';
      }
      return;
    }

    let kesimpulan = '';

    // EVALUASI BERDASARKAN STANDAR TABEL
    if (lila < this.standarLila.gizi_buruk_threshold) {
      kesimpulan = 'Gizi buruk (SAM)'; // < 11.5 cm
    } else if (
      lila >= this.standarLila.gizi_kurang_min &&
      lila <= this.standarLila.gizi_kurang_max
    ) {
      kesimpulan = 'Gizi kurang (MAM)'; // 11.5 - 12.4 cm
    } else if (lila >= this.standarLila.gizi_baik_min) {
      kesimpulan = 'Gizi baik (Normal)'; // ≥ 12.5 cm
    } else {
      kesimpulan = 'Data tidak valid';
    }

    kesimpulanField.value = kesimpulan;
    kesimpulanField.className = 'form-control bg-light';
  }
  initializeLingkarKepalaData() {
    this.standarLingkarKepala = {
      L: [
        // Laki-laki
        { min_bulan: 0, max_bulan: 6, min_lk: 34.0, max_lk: 43.5 },
        { min_bulan: 6, max_bulan: 12, min_lk: 43.5, max_lk: 46.0 },
        { min_bulan: 12, max_bulan: 24, min_lk: 46.0, max_lk: 48.3 },
        { min_bulan: 24, max_bulan: 36, min_lk: 48.3, max_lk: 49.5 },
        { min_bulan: 36, max_bulan: 48, min_lk: 49.5, max_lk: 50.3 },
        { min_bulan: 48, max_bulan: 60, min_lk: 50.3, max_lk: 50.8 },
      ],
      P: [
        // Perempuan
        { min_bulan: 0, max_bulan: 6, min_lk: 34.0, max_lk: 42.0 },
        { min_bulan: 6, max_bulan: 12, min_lk: 42.0, max_lk: 45.0 },
        { min_bulan: 12, max_bulan: 24, min_lk: 45.0, max_lk: 47.2 },
        { min_bulan: 24, max_bulan: 36, min_lk: 47.2, max_lk: 48.5 },
        { min_bulan: 36, max_bulan: 48, min_lk: 48.5, max_lk: 49.4 },
        { min_bulan: 48, max_bulan: 60, min_lk: 49.4, max_lk: 50.0 },
      ],
    };
  }
  // ✅ 3. TAMBAH METHOD BARU
  getJenisKelaminFromNIK(nik) {
    if (!nik || nik.length < 16) return null;
    const digitKe17 = parseInt(nik.charAt(15));
    return digitKe17 % 2 === 1 ? 'L' : 'P';
  }

  // ✅ 4. TAMBAH METHOD BARU - EVALUASI LINGKAR KEPALA
  // ✅ GANTI METHOD evaluasiLingkarKepala() YANG ADA DENGAN INI:
  evaluasiLingkarKepala() {
    const umurField = document.getElementById('umur');
    const lingkarKepalaField = document.getElementById('lingkar_kepala');
    const kesimpulanField = document.getElementById('kesimpulan_lingkar_kepala');
    const nikField = document.getElementById('nik_balita');

    // Skip jika element tidak ada (tidak merusak form lain)
    if (!umurField || !lingkarKepalaField || !kesimpulanField || !nikField) {
      return;
    }

    const umur = parseInt(umurField.value) || 0;
    const lingkarKepala = parseFloat(lingkarKepalaField.value) || 0;
    const nik = nikField.value;
    const jenisKelamin = this.getJenisKelaminFromNIK(nik);

    // Reset jika input kosong
    if (!umur || !lingkarKepala || !jenisKelamin) {
      kesimpulanField.value = '';
      kesimpulanField.className = 'form-control bg-light'; // ✅ UNIFORM CLASS
      return;
    }

    const standar = this.standarLingkarKepala[jenisKelamin];
    if (!standar) {
      kesimpulanField.value = 'Jenis kelamin tidak valid';
      kesimpulanField.className = 'form-control bg-light'; // ✅ UNIFORM CLASS
      return;
    }

    let kesimpulan = 'Umur di luar standar';

    for (let range of standar) {
      if (umur >= range.min_bulan && umur < range.max_bulan) {
        if (lingkarKepala < range.min_lk) {
          kesimpulan = 'Kurang dari normal';
        } else if (lingkarKepala > range.max_lk) {
          kesimpulan = 'Melebihi normal';
        } else {
          kesimpulan = 'Normal';
        }
        break;
      }
    }

    // ✅ SET HASIL DENGAN CLASS UNIFORM (SAMA SEPERTI FIELD LAIN)
    kesimpulanField.value = kesimpulan;
    kesimpulanField.className = 'form-control bg-light'; // ✅ SAMA SEPERTI FIELD KESIMPULAN LAINNYA
  }
  initializeEventListeners() {
    console.log('=== BALITA HANDLER INITIALIZATION ===');

    const formBalita = document.getElementById('form-balita');
    if (!formBalita) {
      console.log('❌ Form balita not found');
      return;
    }

    console.log('✅ Form balita found, setting up handlers...');

    // ✅ GET BASELINE EXAMINATION DATA
    this.fetchLastExaminationData();

    // document.querySelectorAll('#bb, #tb, #umur, #lingkar_kepala').forEach((input) => {
    //   input.addEventListener('input', () => {
    //     this.calculateAndUpdateResults();

    //     // ✅ TAMBAH EVALUASI LINGKAR KEPALA
    //     if (input.id === 'lingkar_kepala' || input.id === 'umur') {
    //       this.evaluasiLingkarKepala();
    //     }
    //   });
    // });
    document.querySelectorAll('#bb, #tb, #umur, #lingkar_kepala, #lila').forEach((input) => {
      input.addEventListener('input', () => {
        this.calculateAndUpdateResults();

        // ✅ EVALUASI LINGKAR KEPALA
        if (input.id === 'lingkar_kepala' || input.id === 'umur') {
          this.evaluasiLingkarKepala();
        }

        // ✅ EVALUASI LILA
        if (input.id === 'umur') {
          this.validateUmurLila(); // Validasi umur dulu
        }
        if (input.id === 'lila') {
          this.evaluasiLila(); // Evaluasi LILA
        }
      });
    });

    // ✅ 2. TANGGAL CHANGE LISTENER
    const tanggalInput = document.getElementById('tanggal_pemeriksaan');
    if (tanggalInput) {
      tanggalInput.addEventListener('change', () => {
        console.log('📅 Date changed, refetching BASELINE data...');
        this.fetchLastExaminationData();
        this.calculateAndUpdateResults();
      });
    }

    // ✅ 3. TBC SKRINING LISTENERS
    document.querySelectorAll('.skrining-tbc').forEach((checkbox) => {
      console.log('🔍 Adding TBC screening listener to:', checkbox.id);
      checkbox.addEventListener('change', () => {
        this.calculateTBCScreening();
      });
    });

    console.log('✅ Balita handlers attached successfully');

    const nikInput = document.getElementById('nik_balita');
    if (nikInput) {
      nikInput.addEventListener('input', () => {
        this.evaluasiLingkarKepala();
      });
    }
  }

  // ✅ FETCH LAST EXAMINATION DATA
  fetchLastExaminationData() {
    const nikInput = document.getElementById('nik_balita');
    const tanggalInput = document.getElementById('tanggal_pemeriksaan');

    if (!nikInput || !nikInput.value) {
      console.log('📊 No NIK found, skipping baseline data fetch');
      return;
    }

    const nik = nikInput.value;
    const currentDate = tanggalInput ? tanggalInput.value : new Date().toISOString().split('T')[0];

    console.log('📊 Fetching BASELINE data for NIK:', nik, 'before date:', currentDate);

    // ✅ KIRIM CURRENT DATE UNTUK MENCARI DATA BASELINE
    fetch(`/get-last-examination/${nik}?current_date=${currentDate}`)
      .then((response) => response.json())
      .then((baselineData) => {
        console.log('📊 Baseline data found:', baselineData);

        if (baselineData && baselineData.bb) {
          // ✅ STORE BASELINE DATA (BUKAN LAST DATA)
          this.lastBBData = {
            bb: parseFloat(baselineData.bb),
            tanggal: baselineData.tanggal_pemeriksaan,
          };
          console.log('✅ Baseline data stored:', this.lastBBData);
        } else {
          console.log('📊 No baseline data found - pemeriksaan pertama');
          this.lastBBData = null;
        }
      })
      .catch((error) => {
        console.log('📊 Error fetching baseline data:', error);
        this.lastBBData = null;
      });
  }

  // ✅ CALCULATE AND UPDATE RESULTS (EXISTING + NEW BB COMPARISON)
  calculateAndUpdateResults() {
    console.log('=== CALCULATING RESULTS ===');

    const bb = parseFloat(document.getElementById('bb').value);
    const tb = parseFloat(document.getElementById('tb').value);
    const umur = parseInt(document.getElementById('umur').value);

    console.log('Values - BB:', bb, 'TB:', tb, 'Umur:', umur);

    // ✅ EXISTING CALCULATIONS
    const kesimpulanBBU = !isNaN(bb) && !isNaN(umur) ? this.getKesimpulanBBU(bb, umur) : '';
    const kesimpulanTBU = !isNaN(tb) && !isNaN(umur) ? this.getKesimpulanTBU(tb, umur) : '';
    const kesimpulanBBTB = !isNaN(bb) && !isNaN(tb) ? this.getKesimpulanBBTB(bb, tb) : '';

    // ✅ NEW BB COMPARISON CALCULATION
    const statusPerubahanBB = !isNaN(bb) ? this.getStatusPerubahanBB(bb) : '';

    // ✅ UPDATE FORM FIELDS
    document.getElementById('kesimpulan_bbu').value = kesimpulanBBU;
    document.getElementById('kesimpulan_tbuu').value = kesimpulanTBU;
    document.getElementById('kesimpulan_bbtb').value = kesimpulanBBTB;
    document.getElementById('status_perubahan_bb').value = statusPerubahanBB;

    // ✅ TAMBAH BARIS INI SAJA
    this.evaluasiLingkarKepala();
    this.validateUmurLila();
    console.log('✅ Results updated with BB comparison');
  }

  // ✅ NEW METHOD: GET STATUS PERUBAHAN BB
  getStatusPerubahanBB(currentBB) {
    console.log(
      '📊 Calculating BB comparison - Current BB:',
      currentBB,
      'vs Baseline:',
      this.lastBBData
    );

    if (!this.lastBBData) {
      // return 'Pemeriksaan pertama - Data baseline';
      return 'Pemeriksaan pertama ';
    }

    const baselineBB = this.lastBBData.bb; // ✅ DATA LAMA SEBAGAI BASELINE
    const newBB = currentBB; // ✅ DATA BARU SEBAGAI COMPARISON
    const perubahan = newBB - baselineBB; // ✅ PERUBAHAN DARI BASELINE KE BARU
    const persentasePerubahan = (perubahan / baselineBB) * 100;
    const tanggalBaseline = this.formatDate(this.lastBBData.tanggal);

    console.log('📊 COMPARISON LOGIC:', {
      'Baseline (Data Lama)': `${baselineBB}kg pada ${tanggalBaseline}`,
      'Current (Data Baru)': `${newBB}kg hari ini`,
      Perubahan: `${perubahan.toFixed(2)}kg (${persentasePerubahan.toFixed(1)}%)`,
    });

    if (perubahan > 0) {
      // ✅ BERAT BERTAMBAH DARI BASELINE
      if (persentasePerubahan >= 10) {
        return `Dari ${tanggalBaseline} (${baselineBB}kg), berat badan naik signifikan menjadi ${newBB}kg (+${perubahan.toFixed(
          2
        )}kg, +${persentasePerubahan.toFixed(1)}%)`;
      } else if (persentasePerubahan >= 5) {
        return `Dari ${tanggalBaseline} (${baselineBB}kg), berat badan naik menjadi ${newBB}kg (+${perubahan.toFixed(
          2
        )}kg, +${persentasePerubahan.toFixed(1)}%)`;
      } else {
        return `Dari ${tanggalBaseline} (${baselineBB}kg), berat badan naik menjadi ${newBB}kg (+${perubahan.toFixed(
          2
        )}kg, +${persentasePerubahan.toFixed(1)}%)`;
      }
    } else if (perubahan < 0) {
      // ✅ BERAT BERKURANG DARI BASELINE
      const persentaseNegatif = Math.abs(persentasePerubahan);
      if (persentaseNegatif >= 10) {
        return `Dari ${tanggalBaseline} (${baselineBB}kg), berat badan turun signifikan menjadi ${newBB}kg (${perubahan.toFixed(
          2
        )}kg, ${persentasePerubahan.toFixed(1)}%) - Perlu evaluasi`;
      } else if (persentaseNegatif >= 5) {
        return `Dari ${tanggalBaseline} (${baselineBB}kg), berat badan turun cukup banyak menjadi ${newBB}kg (${perubahan.toFixed(
          2
        )}kg, ${persentasePerubahan.toFixed(1)}%) - Perlu perhatian`;
      } else {
        return `Dari ${tanggalBaseline} (${baselineBB}kg), berat badan turun sedikit menjadi ${newBB}kg (${perubahan.toFixed(
          2
        )}kg, ${persentasePerubahan.toFixed(1)}%)`;
      }
    } else {
      // ✅ BERAT SAMA DENGAN BASELINE
      return `Berat badan stabil pada ${newBB}kg (tidak ada perubahan dari ${tanggalBaseline})`;
    }
  }

  formatDate(dateString) {
    const date = new Date(dateString);
    const options = {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
    };
    return date.toLocaleDateString('id-ID', options);
  }

  getKesimpulanBBU(bb, umur) {
    if (umur < 0 || umur > 60) {
      return `⚠️ Umur ${umur} bulan di luar standar (0-60 bulan)`;
    }

    if (bb < 1 || bb > 50) {
      return `⚠️ BB ${bb} kg tampak tidak wajar, periksa kembali`;
    }

    let data = this.zscoreBBU.find((d) => d.umur === umur);

    if (!data) return 'Data tidak tersedia untuk umur ini';

    if (bb < data.sd3) return 'Berat badan sangat kurang';
    if (bb < data.sd2) return 'Berat badan kurang';
    if (bb <= data.sd1plus) return 'Berat badan normal';
    return 'Resiko berat badan lebih';
  }

  getKesimpulanTBU(tb, umur) {
    if (umur < 0 || umur > 60) {
      return `⚠️ Umur ${umur} bulan di luar standar (0-60 bulan)`;
    }

    if (tb < 30 || tb > 150) {
      return `⚠️ TB ${tb} cm tampak tidak wajar, periksa kembali`;
    }

    let data = this.zscoreTBU.find((d) => d.umur === umur);

    if (!data) return 'Data tidak tersedia untuk umur ini';

    if (tb < data.sd3 - 3) return 'Sangat pendek';
    if (tb < data.sd2 - 2) return 'Pendek';
    if (tb <= data.sd2plus + 3) return 'Normal';
    return 'Tinggi melebihi normal';
  }

  // ✅ VERSI SIMPLE - HAPUS SEMUA KETERANGAN KURUNG:
  getKesimpulanBBTB(bb, tb) {
    if (bb < 1 || bb > 50) {
      return `⚠️ BB ${bb} kg tampak tidak wajar`;
    }

    if (tb < 40 || tb > 120) {
      return `⚠️ TB ${tb} cm di luar standar (40-120 cm)`;
    }

    let tbBulat = Math.round(tb);
    let data = this.zscoreBBTB.find((d) => d.tb === tbBulat);

    if (!data) {
      if (tbBulat < 65) {
        data = this.zscoreBBTB.find((d) => d.tb === 65);
      }

      if (!data) {
        return `Data tidak tersedia untuk TB ${tb}cm`;
      }
    }

    // ✅ SIMPLE CLASSIFICATION - TANPA KURUNG APAPUN
    if (bb < data.sd3) return 'Gizi buruk';
    if (bb < data.sd2) return 'Gizi kurang';
    if (bb <= data.sd1plus) return 'Gizi baik';
    if (bb <= data.sd2plus) return 'Beresiko gizi lebih';
    if (bb <= data.sd3plus) return 'Gizi lebih';
    return 'Gizi lebih';
  }

  // ✅ TAMBAH METHOD BARU - CALCULATE TBC SCREENING:
  calculateTBCScreening() {
    console.log('🔍 Calculating TBC screening...');

    const batukTerusMenerus = document.getElementById('batuk_terus_menerus').checked;
    const demam2Minggu = document.getElementById('demam_2_minggu').checked;
    const bbTidakNaik = document.getElementById('bb_tidak_naik').checked;
    const kontakTBC = document.getElementById('kontak_tbc').checked;

    // ✅ HITUNG JUMLAH GEJALA
    let jumlahGejala = 0;
    const gejalaList = [];

    if (batukTerusMenerus) {
      jumlahGejala++;
      gejalaList.push('Batuk terus menerus');
    }
    if (demam2Minggu) {
      jumlahGejala++;
      gejalaList.push('Demam > 2 minggu');
    }
    if (bbTidakNaik) {
      jumlahGejala++;
      gejalaList.push('BB tidak naik/turun');
    }
    if (kontakTBC) {
      jumlahGejala++;
      gejalaList.push('Kontak erat TBC');
    }

    console.log('🔍 TBC Screening Results:', {
      'Jumlah Gejala': jumlahGejala,
      'Gejala Terdeteksi': gejalaList,
      'Perlu Rujukan': jumlahGejala >= 2,
    });

    // ✅ UPDATE FIELD JUMLAH GEJALA
    const jumlahGejalaField = document.getElementById('jumlah_gejala_tbc');
    if (jumlahGejalaField) {
      if (jumlahGejala === 0) {
        jumlahGejalaField.value = 'Tidak ada gejala TBC';
        jumlahGejalaField.className = 'form-control'; // PUTIH BERSIH
      } else {
        jumlahGejalaField.value = `${jumlahGejala} gejala: ${gejalaList.join(', ')}`;
      }
    }

    // ✅ UPDATE FIELD RUJUKAN
    const rujukField = document.getElementById('rujuk_puskesmas');
    if (rujukField) {
      if (jumlahGejala >= 2) {
        rujukField.value = 'RUJUK - Perlu pemeriksaan lebih lanjut di Puskesmas';
        rujukField.className = 'form-control bg-danger text-white font-weight-bold';
      } else if (jumlahGejala === 1) {
        rujukField.value = 'TIDAK RUJUK - Gejala TBC tidak mencukupi';
        rujukField.className = 'form-control bg-success text-white';
      } else {
        // 0 GEJALA - KOSONG DAN PUTIH BERSIH
        rujukField.value = '';
        rujukField.className = 'form-control';
      }
    }

    console.log('✅ TBC screening updated');
  }
}

// ✅ CREATE GLOBAL INSTANCE BUT DON'T AUTO-INITIALIZE
const balitaHandler = new BalitaHandler();

// ✅ GLOBAL FUNCTION FOR EXTERNAL INITIALIZATION
function initializeBalitaHandler() {
  console.log('🔄 Initializing Balita Handler from external call...');
  balitaHandler.initializeEventListeners();
}

// ✅ MAKE GLOBALLY AVAILABLE
window.initializeBalitaHandler = initializeBalitaHandler;

// ✅ OBSERVER FOR DYNAMIC FORM DETECTION
document.addEventListener('DOMContentLoaded', function () {
  console.log('🔧 Balita handler DOM ready, setting up observer...');

  // ✅ MUTATION OBSERVER FOR DYNAMIC FORM INJECTION
  const observer = new MutationObserver(function (mutations) {
    mutations.forEach(function (mutation) {
      mutation.addedNodes.forEach(function (node) {
        if (node.nodeType === 1) {
          // ✅ CHECK IF BALITA FORM WAS ADDED
          if (node.id === 'form-balita' || node.querySelector('#form-balita')) {
            console.log('🔄 Balita form detected in DOM, auto-initializing...');
            setTimeout(() => {
              initializeBalitaHandler();
            }, 100);
          }
        }
      });
    });
  });

  // ✅ OBSERVE FORM CONTAINER
  const formContainer = document.getElementById('form-pemeriksaan');
  if (formContainer) {
    observer.observe(formContainer, {
      childList: true,
      subtree: true,
    });
    console.log('✅ Form observer attached to #form-pemeriksaan');
  }
});
