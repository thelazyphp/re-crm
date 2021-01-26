import app from './app';
import renderVueComponentToString from 'vue-server-renderer/basic';

app.$router.push(context.url.replace('/castro-blog/', '/'));

renderVueComponentToString(app, (err, html) => {
    if (err) {
        throw new Error(err);
    }

    dispatch(html);
});
