// import Swiper bundle with all modules installed
import Swiper from 'swiper';

if (typeof Swiper === 'undefined' || !Swiper) {
  throw new Error('Swiper is not defined');
}

export default Swiper;
export { Swiper };
